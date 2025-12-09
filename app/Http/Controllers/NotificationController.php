<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        // Mengurutkan notifikasi yang sudah dibaca berdasarkan created_at secara desc
        $readNotif = $user->readNotifications->sortByDesc('read_at');

        // Mengurutkan notifikasi yang belum dibaca berdasarkan created_at secara desc
        $unreadNotif = $user->unreadNotifications->sortByDesc('created_at');
        return view('notification.index',compact('user','readNotif','unreadNotif'));
    }

    public function markAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }

    public function markAsReadById($id)
    {
        // Ambil notifikasi yang belum dibaca berdasarkan ID
        $notification = Auth::user()->unreadNotifications()->where('id', $id)->first();

        // Jika notifikasi ditemukan, tandai sebagai dibaca
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back();

    }


}
