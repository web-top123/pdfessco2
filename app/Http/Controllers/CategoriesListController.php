<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesListController extends Controller
{
    public function index()
    {
       
        return \App\Models\Category::withCount('files')
            ->withDepth()
            ->orderBy('name', 'asc')
            ->get()
            ->toFlatTree();
    }
}
