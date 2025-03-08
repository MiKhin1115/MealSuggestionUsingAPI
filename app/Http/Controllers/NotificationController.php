<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display the notifications page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('notifications');
    }
    
    /**
     * Display the notification detail page
     *
     * @return \Illuminate\View\View
     */
    public function detail()
    {
        return view('notification_detail');
    }
} 