<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function paid(Request $request){
      return view('admin.users.index');
    }

    public function expired(Request $request){
        return view('admin.users.index');
      }

      public function free(Request $request){
        return view('admin.users.index');
      }
}
