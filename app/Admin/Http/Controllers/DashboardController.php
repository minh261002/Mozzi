<?php

namespace App\Admin\Http\Controllers;

use App\Admin\Http\Controllers\Controller;

class DashboardController
{
    public function index()
    {
        return view('dashboard.index');
    }
}
