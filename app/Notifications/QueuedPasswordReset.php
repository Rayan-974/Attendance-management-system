<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Queue\ShouldQueue;

class QueuedPasswordReset extends ResetPassword implements ShouldQueue
{
    // Extending the built-in ResetPassword notification but adding ShouldQueue
    // so it pushes the email onto the background queue instead of waiting synchronously.
}
