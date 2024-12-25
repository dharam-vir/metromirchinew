<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function fresh(Request $request){
        return view('admin/leads.index');
    }
    public function complete(Request $request){
        return view('admin/leads.index');
    }

    public function action(Request $request){
        return view('admin/leads.index');
    }
}
