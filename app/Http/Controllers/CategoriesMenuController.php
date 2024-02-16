<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Auth;
use DB;

class CategoriesMenuController extends Controller
{

	public function index()
    {
	$currentuserid = Auth::user()->id;
        $nodes = Category::withCount('files')
        ->withDepth()
        ->orderByRaw("name REGEXP '^\d*[^\da-z&\.\' \-\"\!\@\#\$\%\^\*\(\)\;\:\<\>\,\?\/\~\`\|\_\-]' DESC, name+0, name")
        ->get()
        ->toTree();

	$categories = DB::select('select * from blocked_categories where user_id = ?', [$currentuserid]);
	
	foreach ($categories as $category) {

		$result_temp = json_decode(json_encode($category), True);
		$result = $nodes->find($result_temp["category"]);
		$index_to_add = 0;
		foreach ($nodes as $key => $node) {
			if($result===$nodes[$key]){
				
				unset($nodes[$key]);
	
			}else{
				if($key > $index_to_add and !isset($nodes[$index_to_add])){
					$nodes[$index_to_add] = $node;
					unset($nodes[$key]);
				}
				$index_to_add++;
			}
		}
	}

	return $nodes;
    }

}
