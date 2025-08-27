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
            'active_events' => 5,
            'completion_rate' => 95
        ];

        return view('public.home', compact('stats'));
    }
}
