<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobTagihan;
use Illuminate\Http\Request;

class JobTagihanController extends Controller
{
    public function index()
    {
        $jobs = JobTagihan::with('createdBy')->latest()->paginate(10);
        return view('admin.job-tagihan.index', compact('jobs'));
    }
}
