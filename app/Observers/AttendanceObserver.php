<?php

namespace App\Observers;

use App\Models\Attendance;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class AttendanceObserver
{
    protected $whatsapp;

    public function __construct(WhatsAppService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Handle the Attendance "created" event.
     */
    public function created(Attendance $attendance): void
    {
        $this->checkAndNotify($attendance);
    }

    /**
     * Handle the Attendance "updated" event.
     */
    public function updated(Attendance $attendance): void
    {
        $this->checkAndNotify($attendance);
    }

    protected function checkAndNotify(Attendance $attendance): void
    {
        $user = $attendance->user;
        $phone = $user->phone ?? '+1234567890'; 
        $date = Carbon::parse($attendance->date)->format('M d, Y');
        
        $message = "Hello {$user->name}, your attendance for {$date} has been marked as '{$attendance->status}'.";

        $this->whatsapp->sendMessage($phone, $message);
    }
}
