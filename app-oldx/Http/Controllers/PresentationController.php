<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PresentationController extends Controller
{
    public function about(){
        return view('presentation.about');
    }
    public function contact(){
        return view('presentation.contact');
    }
}
