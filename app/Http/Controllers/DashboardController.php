<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Render the Inertia Vue component instead of a Blade view
        return Inertia::render('Admin/Dashboard');
    }
}