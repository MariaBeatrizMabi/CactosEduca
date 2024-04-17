<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SchoolDetails extends Controller
{
    public function index() {
        return view('schoolDetails');
    }
}
