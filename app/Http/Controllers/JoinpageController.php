<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Event;
use App\Models\Report;
use App\Http\Requests\ReportRequest;

class JoinpageController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Join');
    }
}