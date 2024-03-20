<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
        // Get the currently authenticated user
        $user = Auth::user();
        
        // Check if a user is authenticated
        if ($user) {
            // Return the authenticated user as JSON
            return response()->json($user);
        } else {
            // Return an error response if no user is authenticated
            return response()->json(['error' => 'Unauthenticated'], 401);
        }
    }
    
}
