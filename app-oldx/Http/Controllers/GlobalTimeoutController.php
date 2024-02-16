<?php

namespace App\Http\Controllers;

use App\Models\GlobalTimeout;
use App\Http\Requests\GlobalTimeoutRequest;


use Illuminate\Http\Request;

class GlobalTimeoutController extends Controller
{
    public function update(GlobalTimeoutRequest $request)
    {
        $timeout = GlobalTimeout::all()->first();
        $timeout->timeout = $request->timeout;
        $timeout->save();
        return $timeout;
    }
}
