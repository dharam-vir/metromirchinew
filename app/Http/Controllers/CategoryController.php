<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request){
        return response()->json(['error' => 'Category Add.'], 200);
    }

    public function Show(Request $request){
        return response()->json(['error' => 'Category Add.'], 200);
    }

    public function Update(Request $request){
        return response()->json(['error' => 'Category Add.'], 200);
    }

    public function Delete(Request $request){
        return response()->json(['error' => 'Category Add.'], 200);
    }

    public function deActive(Request $request){
        return response()->json(['error' => 'Category Add.'], 200);
    }
}
