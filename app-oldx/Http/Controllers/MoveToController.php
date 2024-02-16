<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use Illuminate\Http\Request;

class MoveToController extends Controller
{

    protected $maxDepth = 2;

    public function index(Request $request)
    {
        if (!$request->has('categories')) {
            return response()->json('no_categories_sent', 422);
        }

        $min;
        $max;

        $levels = collect($request->categories)->flatMap(function ($categoryId) {
            return Category::withDepth()->descendantsAndSelf($categoryId);
        })
        ->each(function($category) use(&$min, &$max) {
            if (!isset($min) || $min > $category->depth) {
                $min = $category->depth;
            }

            if (!isset($max) || $max < $category->depth) {
                $max = $category->depth;
            }
        });

        return $this->maxDepth - ($max - $min);
    }

    public function update(Request $request)
    {
        $categories = collect($request->data['categories'])
        ->flatMap(function ($id) {
            return Category::descendantsAndSelf($id)->map(function ($cat) { return $cat->id; });
        });

        //MOVE CATEGORIES
        $moved = $categories->flatMap(function ($category) use ($request) {
            $update = Category::findOrFail($category);
            $update->parent_id = $request->data['category'];
            $update->save();

            return Category::with('files')->withCount('files')->withCount('children')->withDepth()->descendantsAndSelf($category)->toTree();
        });

        //MOVE FILES
        $files = File::with('user')
        ->whereIn('id', $request->data['files'])       
        ->get()
        ->each(function($file) use ($request) {
            $file->update(['category_id' => $request->data['category']]);
            $file->save();
            $file->breadcrumbs();
        });

        return response()->json(['files' => $files, 'categories' => $moved, 'parent_id' => $request->data['category']]);
    }
}
