<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Routing\Controller;       
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $admin = Auth::guard('admin')->user();
        
        // Fetch statistics
        $totalUsers = \App\Models\NormalUser::count();
        $totalDocuments = \App\Models\Chat::count();
        $recentDocuments = \App\Models\Chat::where('created_at', '>=', now()->subDays(7))->count();
        $totalApiCalls = \App\Models\Chat::count(); // Each chat represents an API call
        
        // Calculate this month's API calls
        $apiCallsThisMonth = \App\Models\Chat::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->count();
        
        return view('admin.dashboard', compact(
            'admin',
            'totalUsers',
            'totalDocuments',
            'recentDocuments',
            'totalApiCalls',
            'apiCallsThisMonth'
        ));
    }
}
