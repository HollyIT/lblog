<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function handle()
    {
        return redirect(route('admin.posts.index'));
//        return view('admin.index', [
//            'pageTitle' => 'Dashboard',
//        ]);
    }
}
