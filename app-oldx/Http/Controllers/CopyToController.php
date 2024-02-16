<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Services\PdfCounter;
use Illuminate\Http\File as FileTool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class CopyToController extends Controller
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
        $newFiles;
        $storagePath  = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
        $requestFiles = collect($request->data['files'])->map(function($id){
            return File::findOrFail($id);
        })->each(function($file) use ($request, $storagePath, &$newFiles){
            $newPath = 'public/uploaded/'.str_random(40).'.pdf';
            $newFile;
            Storage::copy($file->getOriginal('path'), $newPath);           
            $newFile = \Auth::user()->files()->create([
                'name' => $file->name . ' (Copy)',
                'real_name' => $file->real_name,
                'category_id' => (int)$request->data['category'] !== 0 ? $request->data['category'] : null,
                'path' => $newPath,
                'description' => $file->description,
            ]);
            $newFile->load('user');
            $newFile->breadcrumbs();
            $counter = new PdfCounter;
            $newFile['count'] = $counter->count($newFile);
            $newFiles[] = $newFile;


        });
    

        return response()->json(['files' => $newFiles, 'categories' => [], 'parent_id' => $request->data['category']]);
    }
}
