<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\MoveCategoryRequest;
use App\Services\PdfCounter;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{

    public function index($id)
    {
        $category = Category::withDepth()
        ->with('files')
        ->with(['children' => function ($q){
            $q->withCount('files')
            ->withCount('children')
            ->withDepth()
            ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name");
        }])
        ->withCount('files')
        ->withCount('children')
        ->withDepth()
        ->findOrFail($id);
        $category->files = $category->files->map( function ($file) {
            $counter = new PdfCounter;
            $file['count'] = $counter->count($file);
            return $file;
        });
        return $category;
    }

    public function store(CreateCategoryRequest $request)
    {
        $depth = 0;
        if (!$request->id) {
            $category = Category::create(['name' => $request->name]);
        } else {
            $parent = Category::withDepth()->find($request->id);
            $depth = $parent->depth + 1;
            $category = Category::create(['name' => $request->name], $parent);
        }
        $category->files;
        $category->filesCount;
        $category->depth = $depth;
        return $category;
    }

    public function update($id, CategoryUpdateRequest $request)
    {
        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->save();
        return $category;
    }

    public function delete(Request $request)
    {
        Category::destroy($request->data['categories']);
        File::destroy($request->data['files']);
        return response()->json('Items deleted');
    }

    public function move(Request $request)
    {
        $categories = collect($request->data['categories'])
        ->map(function ($id) {
            return Category::descendantsAndSelf($id)->toFlatTree()->map(function ($cat) { return $cat->id; });
        })
        ->flatten();
        $files = File::with('user')
        ->whereIn('id', $request->data['files'])
        ->orWhereIn('category_id', $categories)
        ->get()
        ->each( function($file) use ($request) {
            $file->update(['category_id' => $request->data['category']]);
            $file->save();
        });
        return $files;
    }

    public function moveCat(MoveCategoryRequest $request) {

        $update = Category::findOrFail($request->category);
        $update->parent_id = $request->parent;
        $update->save();
        $category = Category::with('files')->withCount('files')->withDepth()->descendantsAndSelf($request->category)->toTree();

        return $category;
    }
    public function moveUp(Request $request) {

        $update = Category::findOrFail($request->category);
        $ancestor = Category::findOrFail($update->parent_id);

        if (!$ancestor) {
            return "error";
        }

        if (!$ancestor->parent_id) {
            $update->saveAsRoot();
        } else {

            $update->parent_id = $ancestor->parent_id;
            $update->save();
        }

        return Category::withDepth()
        ->with('files')
        ->with(['children' => function ($q){ $q->withCount('files')->withCount('children')->withDepth()->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name"); }])
        ->withCount('files')
        ->withCount('children')
        ->withDepth()
        ->findOrFail($request->category);
    }
}
