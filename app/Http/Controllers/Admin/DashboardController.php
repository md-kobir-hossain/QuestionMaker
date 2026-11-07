<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cq;
use App\Models\Mcq;
use App\Models\Paper;
use App\Models\SchoolClass;
use App\Models\ShortQuestion;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'classes' => SchoolClass::count(),
            'mcqs' => Mcq::count(),
            'shorts' => ShortQuestion::count(),
            'cqs' => Cq::count(),
            'papers' => Paper::count(),
            'users' => User::where('role', 'user')->count(),
        ]);
    }
}
