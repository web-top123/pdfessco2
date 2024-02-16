<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\File;
use App\Http\Requests\CreatePdfRequest;
use App\Services\Pdf;
use App\Services\PdfCounter;

use Illuminate\Http\Request;

class DocumentController extends Controller
{

    public function index($id) {
        $file = File::findOrFail($id);
        $counter = new PdfCounter();
        $count = $counter->count($file);
        return response()->json(['count' => $count]);
    } 

    public function store(CreatePdfRequest $request)
    {
        $document = new Pdf;
        if ($request->removeNumbering) {
            $document->removeNumbering();
        }

        $document->addHeader($request->header);
        $document->addFooter($request->footer);

        if($request->cover){
            $document->addCover($request->cover);
        }

        foreach($request->items as $item) {

            if ($item['type'] === 'file') {
                $pages = array_key_exists('pages', $item) ? $item['pages'] : [];
                $document->addFile(File::findOrFail($item['id']), $pages);
            }
            if ($item['type'] === 'divider') {$document->addDivider($item['text']);}

        }

        $path = $document->save();

        Document::create([
            'path' => $path,
            'user_id' => auth()->user()->id,
        ]);

        return url(\Storage::url($path));
    }
}
