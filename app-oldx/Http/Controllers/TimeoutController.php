<?php

namespace App\Http\Controllers;

use App\Models\Timeout;
use App\Models\User;
use DataTables;
use App\Http\Requests\TimeoutUpdateRequest;
use App\Http\Requests\TimeoutCreateRequest;



use Illuminate\Http\Request;

class TimeoutController extends Controller
{
    public function update(TimeoutUpdateRequest $request)
    {
        $timeout = Timeout::updateOrCreate(
            ['user_id' => $request->userId],
            ['timeout' => $request->timeout]
        );
        return $timeout;
    }

    public function delete(Request $request)
    {
        return Timeout::where('user_id', $request->userId)->delete();
    }

    public function dataAdmins()
    {
        return DataTables::eloquent(User::with('timeout')->admins())->make();
    }

    public function dataUsers()
    {
        return DataTables::eloquent(User::with('timeout')->users())->make();
    }
}
