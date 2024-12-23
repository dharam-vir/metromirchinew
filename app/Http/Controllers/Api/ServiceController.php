<?php

namespace App\Http\Controllers\Api;

use App\Models\Services;
use App\Models\Listing;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ServiceController extends Controller
{
    public function Index(Request $request)
    {
        return response()->json(['message' => 'Insufficient Logic.'], 400);
    }

    public function showServices(Request $request)
    {
        try {
            // Get all services where the 'user_id' matches the authenticated user's ID
            $services = Services::where('user_id', operator: auth()->user()->id)->get();

            // Check if the user has any services
            if ($services->isEmpty()) {
                return response()->json(['message' => 'No services found for the authenticated user.'], 404);
            }

            // Return the services
            return response()->json(['services' => $services], 200);
        } catch (\Exception $e) {
            // Handle any unexpected errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function addServices(Request $request)
    {
        // Validate the request data (optional)
        try {
            $validated = $request->validate([
                'sub_cat_id' => 'required|exists:sub_category,id',
                'listing_id' => 'required|exists:listing,id',
                'status' => 'required|in:yes,no',
            ]);
            // Check if the listing belongs to the authenticated user
            $listing = Listing::find($validated['listing_id']);

            // Ensure the listing exists and is created by the authenticated user
            if (!$listing || $listing->user_id !== auth()->user()->id) {
                return response()->json(['error' => 'This listing does not belong to the authenticated user.'], 403);
            }
            // Create a new service with the authenticated user's ID
            Services::create([
                'user_id' => auth()->user()->id,
                'sub_cat_id' => $validated['sub_cat_id'],
                'listing_id' => $validated['listing_id'],
                'status' => $validated['status'],
                'last_activity' => time(),
            ]);
            return response()->json(['message' => 'Service added successfully!'], 200);
        } catch (QueryException $e) {
            // Handle database-related errors (e.g., constraint violations)
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle any other types of errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

    public function deactiveServices(Request $request)
    {
        try {
            // Find the service by ID and ensure it belongs to the authenticated user
            $service = Services::where('sub_cat_id', $request->service_id)
                              ->where('user_id', auth()->user()->id)  // Ensure the service belongs to the logged-in user
                              ->first();

            if (!$service) {
                return response()->json(['error' => 'Service not found or unauthorized action.'], 404);
            }

            // Toggle the status: if it's 'yes', set to 'no', if it's 'no', set to 'yes'
            $newStatus = ($service->status === 'yes') ? 'no' : 'yes';
            $service->status = $newStatus;
            $service->save();

            return response()->json(['message' => 'Service status updated successfully.', 'status' => $newStatus], 200);

        } catch (QueryException $e) {
            // Handle database-related errors
            return response()->json(['error' => 'Database error: ' . $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Handle any other types of errors
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }
}
