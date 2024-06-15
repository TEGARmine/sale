<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $sales = Sales::orderBy('created_at', 'desc')->get();

        return view('dashboard', compact('sales'));
    }
}
