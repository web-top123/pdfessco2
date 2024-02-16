<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Services\PdfExplode;

class ExplodeController extends Controller
{
    public function index(Request $request) {

        $file = File::findOrFail($request->id);

        $files = [];
        $file->breadcrumbs();

        if ($request->pages) {
            foreach($request->pages as $page) {
                $document = new PdfExplode;
                $document->addFile($file, [$page]);
                $path = $document->save();

                $_file = \Auth::user()->files()->create([
                    'name' => $file->name . '-' . $page,
                    'real_name' => $file->real_name,
                    'category_id' => $file->category_id,
                    'path' => $path,
                    'description' => $file->description,
                ]);

                $_file['count'] = 1;
                $_file['breadcrumbs'] = $file['breadcrumbs'];
                $files[] = $_file;
            }
        }
        return $files;
    }
}
