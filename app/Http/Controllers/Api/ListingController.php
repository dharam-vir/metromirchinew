<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ListingController extends Controller {

    public function index( Request $request ) {
        return response()->json( 'indexpahe' );
    }

    public function storeGallery( Request $request, $businessId ) {
        // Validate the request
        $request->validate( [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title' => 'nullable|string|max:255',
        ] );

        // Find the business
        $business = Business::findOrFail( $businessId );

        // Store the image
        $imagePath = $request->file( 'image' )->store( 'gallery', 'public' );

        // Create the photo record and associate it with the business
        $photo = new Photo();
        $photo->business_id = $business->id;
        $photo->image_path = $imagePath;
        $photo->title = $request->input( 'title' );
        $photo->save();

        return response()->json( [ 'status'=>true, 'message'=>'Photo added successfully', 'business_id'=>$businessId ] );
    }

    public function storeListing( Request $request ) {
        $request->validate( [
            'company_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',  // Validation for logo
        ] );

        $listing = new Listing();
        $listing->user_id = auth()->id();
        $listing->company_name = $request->company_name;
        $listing->contact_email = $request->contact_email;
        // Add other fields here...

        // Handle logo upload
        if ( $request->hasFile( 'logo' ) ) {
            // Store the file locally or in the cloud ( e.g., 'public' or 's3' disk )
            $logoPath = $request->file( 'logo' )->store( 'logos', 'public' );
            $listing->logo = $logoPath;
            // Save the file path in the database
        }

        $listing->save();

        return response()->json( [
            'message' => 'Listing submitted successfully!',           
        ] );
    }

    public function submitReview( Request $request, $listingId ) {
        $listing = Listing::find( $listingId );
        $user = auth()->user();

        // Validate review input
        $validated = $request->validate( [
            'rating' => 'required|numeric|min:1|max:5',
            'review' => 'nullable|string',
        ] );

        // Create a new review
        $review = new Review();
        $review->listing_id = $listingId;
        $review->user_id = $user->id;
        $review->rating = $validated[ 'rating' ];
        $review->review = $validated[ 'review' ] ?? null;
        $review->save();

        // Recalculate average rating
        $averageRating = Review::where( 'listing_id', $listingId )->avg( 'rating' );
        $ratingCount = Review::where( 'listing_id', $listingId )->count();

        // Update the listing with the new average rating and count
        $listing->rating = $averageRating;
        $listing->rating_count = $ratingCount;
        $listing->save();

        return response()->json( [
            'message' => 'Review submitted successfully!',
            'new_rating' => $listing->rating,
            'rating_count' => $listing->rating_count
        ] );
    }
}
