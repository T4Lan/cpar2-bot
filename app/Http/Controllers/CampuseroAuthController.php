<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CampuserAuthController extends Controller
{
    function callback(Request $request)
    {
        Log::info($request->all);
    }
}
