<?php

namespace App\Http\Controllers;

use App\Models\User;
use DataTables;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('manage-users');
    }

    public function data()
    {
        return DataTables::eloquent(User::withCount('documents')->users())->make();
    }
}
