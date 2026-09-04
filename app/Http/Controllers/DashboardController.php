<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        return view('dashboard.index');
    }

    public function indexAdmin()
    {
        return view('dashboard.index');
    }
    public function indexCashier()
    {
        return view('dashboard.index');
    }
    public function delete()
    {

    }
}
