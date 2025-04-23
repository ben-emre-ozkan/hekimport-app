<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get featured doctors (active doctors, limited to 8)
        $doctors = Doctor::where('is_active', true)
                        ->orderBy('created_at', 'desc')
                        ->take(8)
                        ->get();

        return view('welcome', compact('doctors'));
    }
} 