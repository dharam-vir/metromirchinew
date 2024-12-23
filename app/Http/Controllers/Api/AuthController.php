<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {

    public function Login(LoginRequest $request) {
    // Validate the incoming request data
    $credentials = $request->validated();
    try {
        // Attempt to authenticate the user with the credentials
        if (Auth::attempt($credentials)) {
            // If authentication is successful, generate a JWT token
            $user = Auth::user(['id', 'name', 'email']);
            $token = JWTAuth::fromUser($user);

            // Return success response with token and user data
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'token' => $token,
                'user' => $user,
            ], 200);
        } else {
            // If authentication fails, return an error response
            return response()->json([
                'status' => false,
                'message' => 'Incorrect email or password!',
            ], 401); // Unauthorized status code
        }
    } catch (\Exception $e) {
        // Log the detailed exception message for internal use
        Log::error('Login error', [
            'message' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile(),
        ]);

        // Return a generic error response to the client
        return response()->json([
            'status' => false,
            'message' => 'An error occurred during login. Please try again later.',
        ], 500); // Internal Server Error status code
    }
}


    public function SingUp( RegisterRequest $request ) {
        // Check if the email already exists
        if ( User::where( 'email', $request->email )->exists() ) {
            // Return a response indicating the email is already taken
            return response()->json( [
                'status' => false,
                'message' => 'Email is already registered'
            ], 400 );
            // HTTP status code 400 for bad request
        }

        try {
            // Create a new user
            $user = User::create( $request->all() );

            // Generate JWT token for the user
            $token = JWTAuth::fromUser( $user );

            // Return a response with the token and user data
            return response()->json( [
                'status' => true,
                'message' => 'Successfully Created',
                'token' => $token,
                'user' => $user
            ] );
        } catch ( \Exception $e ) {
            // Handle other exceptions ( e.g., database errors )
            Log::error( 'SingUp error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ] );

            return response()->json( [
                'status' => false,
                'message' => 'An error occurred. Please try again later.'
            ], 500 );
        }
    }

    public function Logout( Request $request ) {
        try {
            // Get the current token from the request
            $token = JWTAuth::getToken();

            if ( !$token ) {
                return response()->json( [
                    'status' => false,
                    'message' => 'No token provided.',
                ], 400 );
            }

            // Invalidate the token ( this adds it to the blacklist )
            JWTAuth::invalidate( $token );

            return response()->json( [
                'status' => true,
                'message' => 'Successfully logged out.',
            ], 200 );
        } catch ( \Exception $e ) {
            // Handle errors during logout
            Log::error( 'Logout error', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ] );
            return response()->json( [
                'status' => false,
                'message' => 'An error occurred during logout.',
            ], 500 );
        }
    }

    public function checkIfUserIsLoggedIn( Request $request ) {
        try {
            // Get the token from the Authorization header
            $token = JWTAuth::getToken();
            // Retrieve the token from the request

            // Check if no token is provided
            if ( !$token ) {
                return response()->json( [
                    'status' => false,
                    'message' => 'No token provided.',
                ], 400 );
                // Bad Request
            }

            // Try to parse and authenticate the token
            $user = JWTAuth::authenticate( $token );
            // Get the authenticated user from the token

            // Check if the user exists ( valid token )
            if ( !$user ) {
                return response()->json( [
                    'status' => false,
                    'message' => 'User not found or token is invalid.',
                ], 401 );
                // Unauthorized
            }

            // If user is authenticated, return user details
            return response()->json( [
                'status' => true,
                'message' => 'User is logged in.',
                'user' => $user,  // Send user data
            ], 200 );
            // OK response
        } catch ( \Exception $e ) {
            // Handle exception if token is invalid or expired
            return response()->json( [
                'status' => false,
                'message' => 'Token is invalid or expired.',
            ], 401 );
            // Unauthorized
        }
    }
  
}
