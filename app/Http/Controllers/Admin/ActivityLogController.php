<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_activity_log']);
    }
    public function index()
    {
        return view('admin.activity_logs.index')->withActivityLogs(Activity::get());
    }
}
