<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    //users list
    public function usersList() {
        $users = DB::table('users')->get();
        return response()->json($users);
    }
    
}
