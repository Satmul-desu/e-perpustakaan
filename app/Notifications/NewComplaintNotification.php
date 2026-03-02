<?php

namespace App\Notifications;

use App\Models\Complaint;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewComplaintNotification extends Notification
{
    use Queueable;

    protected $complaint;

    /**
     * Create a new notification instance.
     */
    public function __construct(Complaint $complaint)
    {
        $this->complaint = $complaint;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        // Use database only to avoid mail configuration issues
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📢 Aduan/Laporan Baru: ' . $this->complaint->subject)
            ->line('Ada aduan/laporan baru dari pengguna.')
            ->line('')
            ->line('**Detail Aduan:**')
            ->line('Nama: ' . $this->complaint->user->name)
            ->line('Email: ' . $this->complaint->user->email)
            ->line('Jenis: ' . ucfirst($this->complaint->type))
            ->line('Kategori: ' . $this->complaint->category_name)
            ->line('Prioritas: ' . $this->complaint->priority_name)
            ->line('')
            ->line('**Pesan:**')
            ->line($this->complaint->message)
            ->line('')
            ->action('Lihat Aduan', route('admin.complaints.show', $this->complaint))
            ->line('Segera tindaklanjuti aduan ini.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        return [
            'complaint_id' => $this->complaint->id,
            'user_id' => $this->complaint->user_id,
            'user_name' => $this->complaint->user->name,
            'type' => $this->complaint->type,
            'category' => $this->complaint->category,
            'subject' => $this->complaint->subject,
            'message' => $this->complaint->message,
            'priority' => $this->complaint->priority,
            'status' => $this->complaint->status,
            'order_number' => $this->complaint->order_number,
        ];
    }
}

