<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use App\Http\Requests\FileUploadRequest;
use App\Http\Requests\UpdateFileRequest;
use Illuminate\Http\Request;
use DB;
use Auth;


class FilesController extends Controller
{
 
    public function index(Request $request)
    {

        $nodes = $request->cat ? Category::withCount('files')
            ->descendantsAndSelf($request->cat)
            ->filter(function ($item) {
                return $item->files_count;
            })
            ->map(function ($item) {
                return $item->id;
            }) : [];

        $filtered_files = File::where(function ($query) use ($request, $nodes) {
            if ($request->q) {
                $query->where('name', 'LIKE', '%'.$request->q .'%');
            }

            if ($request->cat) {
                $query->whereIn('category_id', $nodes);
            }
        })
        ->hasCategory()
        ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name")
        ->simplePaginate(15);


 $currentuserid = Auth::user()->id;
        $categories = DB::select('select * from blocked_categories where user_id = ?', [$currentuserid]);
    

    foreach ($categories as $category) {

        $result_temp = json_decode(json_encode($category), True);
        $index_to_add = 0;
        foreach ($filtered_files as $key => $node) {

            if($result_temp["category"] ===$filtered_files[$key]["category_id"]){
                
                unset($filtered_files[$key]);
    
            }else{
                if($key > $index_to_add and !isset($filtered_files[$index_to_add])){
                    $filtered_files[$index_to_add] = $node;
                    unset($filtered_files[$key]);
                }
                $index_to_add++;
            }
        }
    }


	return $filtered_files;
    }


    public function store(FileUploadRequest $request)
    {
       try{
        if(\Auth::check())
        {
            $path = $request->file->store('public/uploaded');
            $file = \Auth::user()->files()->create([
                'name' => $request->title,
                'real_name' => $request->file->getClientOriginalName(),
                'category_id' => (int)$request->category != 0 ? $request->category : null,
                'path' => $path,
                'description' => $request->description,
            ]);
    
            $file->load('user');
            $file->breadcrumbs();
    
            return $file;
        }else{
            return 'Please Login';
        }

    }catch(\Exception $e)
    {
        return $e->getMessage();
    }
    }

    public function update($id, UpdateFileRequest $request)
    {
        $file = File::findOrFail($id);

        $file->name = $request->name;
        $file->description = $request->description;
        $file->save();
        $file->breadcrumbs();

        return $file;
    }
}
