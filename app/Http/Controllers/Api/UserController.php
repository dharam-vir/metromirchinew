<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class UserController extends Controller {
    
    public function Index( Request $request ) {
        // You can set a custom number of items per page
        $perPage = $request->input( 'per_page', 10 );
        // Default is 10 items per page

        // Paginate users
        $users = User::paginate( $perPage );
        // Paginate with $perPage items

        return response()->json( [
            'status' => true,
            'message' => 'Users fetched successfully.',
            'data' => $users,
        ], 200 );
    }

    public function Create( Request $request ) {
        return response()->json( 'sdsdsdsd' );
    }

    public function Update( Request $request ) {
        return response()->json( 'sdsdsdsd' );
    }
}
