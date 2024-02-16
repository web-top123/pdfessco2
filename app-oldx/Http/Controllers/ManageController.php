<?php

namespace App\Http\Controllers;

use App\Queries\CategoriesListQuery;
use App\Queries\CategoriesQuery;
use Illuminate\Http\Request;
use Auth;
use DB;

class ManageController extends Controller
{


	public function index()
    {
      
        return view('manage');
    }
    public function categoryPermissions()
    {
        return view('manage-categories');
    }
    public function categoryPermissionstemp()
    {
        return view('manage-categoriestemp');
    }

    public function changeCategoryPermissions(Request $request)
    {
	$length = count($request['id']);
	$current_blocks = DB::table('blocked_categories')->where('user_id', $request['id'][$length-1])->get(['category']);
	$current_blocks = json_decode(json_encode($current_blocks), True);
	foreach($current_blocks as $block){
		if(!in_array($block['category'], $request['id'])){
			DB::delete('delete from blocked_categories where user_id = ? and category = ?',[$request['id'][$length-1],$block['category']]);
		}
	}
	for ($i = 0; $i < $length-1; $i++) {
		$flag = 1;		
		foreach($current_blocks as $block){
			if($request['id'][$i] == $block['category']){
				$flag = 0;
				break;
				}
		}
		if($flag==1){
			DB::insert('insert into blocked_categories (user_id, category) values (?, ?)', [$request['id'][$length-1],$request['id'][$i]]);
		}
	}

	header("Location: http://pdfglue.com/");
    }

}
