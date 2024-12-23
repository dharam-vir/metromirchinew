<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\SubCategory;
use App\Models\Services;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function addMoney(Request $request)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Get the authenticated user's ID
        $userId = auth()->user()->id;

        // Validate incoming request data
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        // Start the transaction
        DB::beginTransaction();

        try {
            // Find or create the wallet for the user
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $userId],  // Check if wallet exists for the user
                ['balance' => 0.00]      // Set initial balance to 0 if the wallet is being created
            );

            // Add money to the wallet
            $wallet->addMoney($request->amount);
            $wallet->save();

            // Record the transaction
            Transaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $request->amount,
                'type' => 'Deposit',          // Type of transaction (Deposit)
                'description' => $request->description ?? 'No description',  // Default description if none provided
            ]);

            // Commit the transaction if everything is successful
            DB::commit();

            // Return a success response with updated wallet balance
            return response()->json([
                'message' => 'Money added successfully!',
                'wallet_balance' => $wallet->balance,
            ]);

        } catch (\Exception $e) {
            // Rollback the transaction if an error occurs
            DB::rollBack();
            // Optionally, you can log the exception or return the error message
            return response()->json(['message' => 'An error occurred while processing the transaction.'], 500);
        }
    }
    // Get transaction history
    public function showTransactionHistory(Request $request)
    {
        // Ensure the user is authenticated
        if (!auth()->check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        // Get the authenticated user's ID
        $userId = auth()->user()->id;

        // Find the wallet for the authenticated user
        $wallet = Wallet::where('user_id', $userId)->first();

        // If wallet does not exist, return an error
        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found'], 404);
        }

        // Fetch the transactions associated with the user's wallet
        $transactions = Transaction::where('wallet_id', $wallet->id)
            ->orderBy('created_at', 'desc')  // Order by most recent transaction
            ->paginate(10);  // Paginate results, 10 per page (adjust as needed)

        // Return the transactions as a JSON response
        return response()->json([
            'transactions' => $transactions,
            'message' => 'Transaction history retrieved successfully.',
        ]);
    }

    public function spendMoney(Request $request)
    {
        // Validate the request
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);
    
        // Get the authenticated user's ID
        $userId = auth()->user()->id;
    
        // Find the wallet for the authenticated user
        $wallet = Wallet::where('user_id', $userId)->first();
    
        // Check if the wallet exists
        if (!$wallet) {
            return response()->json(['message' => 'Wallet not found.'], 404);
        }
    
        // Start a transaction to ensure atomicity
        DB::beginTransaction();
    
        try {
            // Try to spend money from the wallet
            $spendSuccessful = $wallet->spendMoney($request->amount);
    
            // If the spend was successful, record the transaction
            if ($spendSuccessful) {
                Transaction::create([
                    'wallet_id' => $wallet->id,
                    'amount' => $request->amount,
                    'type' => 'withdrawal',  // Transaction type is 'withdrawal'
                    'description' => $request->description ?? 'Spent money from wallet',
                ]);
    
                // Commit the transaction if everything goes well
                DB::commit();
    
                return response()->json([
                    'message' => 'Money spent successfully!',
                    'new_balance' => $wallet->balance,
                ]);
            }
    
            // If insufficient balance, throw an exception to trigger the rollback
            throw new \Exception('Insufficient balance.');
    
        } catch (\Exception $e) {
            // Rollback the transaction in case of any error
            DB::rollBack();
    
            // Return the error message
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }


    public function spendForLeads(Request $request)
    {
        // Validate the input
        $request->validate([
            'cat_id' => 'required|integer|min:1',
        ]);
    
        // Get the authenticated user's ID
        $userId = auth()->user()->id;
    
        // Start a database transaction to ensure atomic operations
        DB::beginTransaction();
    
        try {
            // Find the wallet associated with the authenticated user
            $wallet = Wallet::where('user_id', $userId)->first();
    
            // Check if the wallet exists
            if (!$wallet) {
                return response()->json(['message' => 'Wallet not found.'], 404);
            }
    
            // Fetch the price for a lead from the prices table
            $leadSubCategory = SubCategory::select(['id', 'name', 'price'])->where('id', $request->cat_id)->first();
    
            // Check if the subcategory exists
            if (!$leadSubCategory) {
                return response()->json(['message' => 'Lead price not found.'], 404);
            }
    
            // Check if the user has any services under this subcategory
            $service = Services::where('sub_cat_id', $leadSubCategory->id)->where('user_id', $userId)->first();
    
            // Calculate the total amount to spend
            // If the user has no service under this subcategory, allow spending, otherwise return error
            if (!$service) {
                $amountToSpend = $leadSubCategory->price;
            } else {
                return response()->json(['message' => 'You already have a service under this subcategory.'], 400);
            }
    
            // Check if the wallet has sufficient balance
            if ($wallet->balance < $amountToSpend) {
                return response()->json(['message' => 'Insufficient balance.'], 400);
            }
    
            // Deduct the amount from the wallet
            $wallet->balance -= $amountToSpend;
            $wallet->save();
    
            // Log the transaction for the spending
            Transaction::create([
                'wallet_id' => $wallet->id,
                'amount' => $amountToSpend,
                'type' => 'withdrawal',  // Type is 'withdrawal' for spending
                'description' => "Spent for {$leadSubCategory->name} leads",
            ]);
    
            // Commit the transaction to save all changes
            DB::commit();
    
            // Return success response with updated balance
            return response()->json([
                'message' => 'Money spent successfully!',
                'new_balance' => $wallet->balance,
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction if something goes wrong
            DB::rollback();
            
            // Return error message
            return response()->json(['message' => 'An error occurred. Please try again later.'], 500);
        }
    }
    
}
