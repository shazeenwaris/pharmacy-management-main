<?php

namespace App\Http\Controllers;

use App\Models\CashierNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CashierNotificationController extends Controller
{
    public function markAsRead(
        Request $request,
        CashierNotification $notification
    ): JsonResponse {

        $notification->update([
            'read_at' => now(),
        ]);

        return response()->json([
            'success' => true,
        ]);
    }


    public function markAllAsRead(
        Request $request
    ): JsonResponse {

        CashierNotification::query()
            ->unread()
            ->update([
                'read_at' => now(),
            ]);

        return response()->json([
            'success' => true,
        ]);
    }
}