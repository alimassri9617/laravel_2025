<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DriverController extends Controller
{
    function dashboard()
    {
        return view("driver");
    }
}
