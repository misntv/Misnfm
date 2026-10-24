<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileAdminController extends Controller
{
    public function show()
    {
        $admin = Auth::user(); // Pastikan admin login
        return view('admin.profile.show', compact('admin'));
    }
}