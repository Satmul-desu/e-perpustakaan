<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanOverdueNotification extends Notification
{
    use Queueable;

    protected $loan;
    protected $isAdminAlert;

    public function __construct(Loan $loan, $isAdminAlert = false)
    {
        $this->loan = $loan;
        $this->isAdminAlert = $isAdminAlert;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $fineAmount = 'Rp ' . number_format($this->loan->fine_amount, 0, ',', '.');
        
        if ($this->isAdminAlert) {
            return [
                'title' => 'Denda Keterlambatan',
                'message' => "Buku {$this->loan->book->name} terlambat dari User {$this->loan->user->name}. Total denda {$fineAmount}",
                'loan_id' => $this->loan->id,
                'fine_amount' => $this->loan->fine_amount,
                'type' => 'fine'
            ];
        }
        
        return [
            'title' => 'Buku Melewati Batas Waktu!',
            'message' => "Anda telah telat mengembalikan buku {$this->loan->book->name}. Total denda sementara: {$fineAmount}",
            'loan_id' => $this->loan->id,
            'fine_amount' => $this->loan->fine_amount,
            'type' => 'fine'
        ];
    }
}
