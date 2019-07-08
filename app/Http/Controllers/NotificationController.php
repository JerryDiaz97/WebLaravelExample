<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;

class NotificationController extends Controller
{
    public function get() {
        //return Notification::all();
        $unreadNotifications = Auth::user()->unreadNotifications;
        $actualDate = date('Y-m-d');
        foreach ($unreadNotifications as $notifications) {
            if($actualDate != $notifications->created_at->toDateString()){
                $notifications->markAsRead();
            }
        }
        return Auth::user()->unreadNotifications;
    }
}
