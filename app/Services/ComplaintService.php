<?php

namespace App\Services;

use App\Models\Complaint;
use App\Models\User;
use App\Notifications\NewComplaintNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ComplaintService
{
    public function createComplaint(User $user, array $data): Complaint
    {
        return DB::transaction(function () use ($user, $data) {
            $complaint = Complaint::create([
                'user_id' => $user->id,
                'type' => $data['type'],
                'category' => $data['category'],
                'subject' => $data['subject'],
                'message' => $data['message'],
                'order_number' => $data['order_number'] ?? null,
                'priority' => $data['priority'] ?? 'normal',
                'status' => 'pending',
            ]);

            try {
                $admins = User::where('role', 'admin')->get();
                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new NewComplaintNotification($complaint));
                }
            } catch (\Exception $e) {
                \Log::error('Failed to send complaint notification: ' . $e->getMessage());
            }

            return $complaint;
        });
    }
}