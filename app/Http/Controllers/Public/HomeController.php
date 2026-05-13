<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index(): View
    {
        // Get some basic statistics for homepage (optional)
        $stats = [
            'total_participants' => 300, // This can be dynamic from database later
            'active_programs' => 5, // active_events -> active_programs
            'completion_rate' => 95
        ];

        return view('public.home', compact('stats'));
    }
}
