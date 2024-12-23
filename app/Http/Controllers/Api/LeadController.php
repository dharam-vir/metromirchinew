<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Clients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class LeadController extends Controller
{
  public function index(Request $request)
  {

  }

  public function Create(Request $request)
  {
      try {
          // Validate the incoming request data
          $validated = $request->validate([
              'listing_id' => 'nullable|exists:listing,id',
              'service_id' => 'required',
              'description' => 'required|string|max:1000',
              'name' => 'required|string|max:255', // Validation for client name
              'email' => 'nullable|email|max:255', // Validation for client email
              'primary_number' => 'required|string|max:15', // Validation for primary number                 
          ]);

           // Check if the client already exists by email or primary number (or another unique field)
        $client = Clients::where('email', $validated['email'])
        ->orWhere('primary_number', $validated['primary_number'])
        ->first();

  
          // Insert the client details first
          if (!$client) {
          $client = Clients::create([
              'name' => $validated['name'],
              'email' => $validated['email'],
              'primary_number' => $validated['primary_number'],  
              'zipcode' => $validated['zipcode'],
              'city' => $validated['city'],
          ]);
        }
  
          // Check if the client was created successfully
          if (!$client) {
              return response()->json([
                  'message' => 'Failed to create client.',
              ], 400); // Return a 400 Bad Request if client creation failed
          }
  
          // Create the lead using the validated data and newly created client      
          $lead = Lead::create([
              'listing_id' => $validated['listing_id'],
              'service_id' => $validated['service_id'],
              'description' => $validated['description'],
              'status' => 1,
              'client_id' => $client->id, // Use the newly created client's ID
          ]);
  
          // Return a success response
          return response()->json([
              'message' => 'Lead and client created successfully!',
              'data' => $lead
          ], 201); // 201 means created
  
      } catch (Exception $e) {
          // Log the exception for debugging purposes
          Log::error('Error creating lead: ' . $e->getMessage());
          // Return error response
          return response()->json([
              'message' => 'An error occurred while creating the lead.',
              'error' => $e->getMessage()
          ], 500); // 500 means server error
      }
  }
  
  public function showLead(Request $request)
  {
    $lead = Lead::with('listing')->findOrFail(auth()->user()->id);
    return response()->json([
      'message' => 'Total Leads',
      'data' => $lead
    ]);
  }

  public function anyData(Request $request)
  {
    return response()->json([
      'message' => 'Total Leads'
    ]);
  }


  public function updateFollowup(Request $request)
  {
    return response()->json([
      'message' => 'Total Leads'
    ]);
  }


  public function updateAssign(Request $request)
  {
    return response()->json([
      'message' => 'Total Leads'
    ]);
  }

  public function updateStatus(Request $request)
  {
    return response()->json([
      'message' => 'Total Leads'
    ]);
  }
}
