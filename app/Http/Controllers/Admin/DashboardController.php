<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    // Display the dashboard
    public function index()
    {
        return Inertia::render('Admin/Dashboard'); // Ensure the corresponding Vue component exists
    }
}
