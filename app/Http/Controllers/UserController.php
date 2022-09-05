<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function test(Request $Request){
    return response()->json([
        'test'=>'Perfectly'
    ], 200);;
    }
}
