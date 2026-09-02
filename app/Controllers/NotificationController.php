<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\Notification;

final class NotificationController
{
    public function markRead(int $id): void
    {
        Notification::markRead($id);
        redirect('dashboard');
    }
}
