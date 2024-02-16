<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\File;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // dd(Category::all());
        return view('dashboard');
    }
}
