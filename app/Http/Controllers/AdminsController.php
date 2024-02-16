<?php

namespace App\Http\Controllers;

use App\Models\User;
use DataTables;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    public function index()
    {
        return view('manage-admins');
    }

    public function data()
    {
        return DataTables::eloquent(User::withCount('documents')->admins())->make();
    }

}
