<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use App\Services\PdfCounter;

class AdminSearchController extends Controller
{
    public function index(Request $request)
    {
        // return $request->all();
        return File::where(function ($q) use ($request) {

            if ($request->has('q')) {
                $q->where('name', 'like', '%' . $request->q . '%');
            }
        })
        ->hasCategory()
        ->with('user')
        ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name")
        ->simplePaginate(20)
        ->each(function ($file) {
            $file->breadcrumbs();
            // $counter = new PdfCounter;
            // $file['count'] = $counter->count($file);
        });
    }
}
