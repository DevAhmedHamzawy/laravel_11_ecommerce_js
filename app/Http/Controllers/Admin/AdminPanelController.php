<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;

class AdminPanelController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', ['users_count' => User::count(), 'admins_count' => Admin::count()]);
    }
}
