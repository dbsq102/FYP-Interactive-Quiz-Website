<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use App\Models\Groups;
use App\Models\History;
use Illuminate\Http\Request;
use Session;
use DB;
use Auth;

class ReportsController extends Controller
{
    public function reportsView() {
        return view('reports');
    }
}