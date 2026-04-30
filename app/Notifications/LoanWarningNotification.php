<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanWarningNotification extends Notification
{
    use Queueable;

    protected $loan;
    protected $warningLevel;
    protected $isAdminAlert;

    public function __construct(Loan $loan, int $warningLevel, bool $isAdminAlert = false)
    {
        $this->loan = $loan;
        $this->warningLevel = $warningLevel;
        $this->isAdminAlert = $isAdminAlert;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $bookName = $this->loan->book->name;

        if ($this->isAdminAlert) {
            $msg = $this->warningLevel === 1 
                ? "Buku $bookName telat 1 hari oleh {$this->loan->user->name}." 
                : "Buku $bookName telat 2 hari oleh {$this->loan->user->name}. Butuh tindakan segera.";
            
            return [
                'title' => 'Peringatan Admin! (' . ($this->warningLevel == 1 ? 'Segera Kembali' : 'Peringatan Terakhir') . ')',
                'message' => $msg,
                'loan_id' => $this->loan->id,
                'type' => 'warning'
            ];
        }

        $title = $this->warningLevel === 1 ? 'Segera Dikembalikan' : 'Peringatan Terakhir';
        $message = $this->warningLevel === 1 
            ? "Buku $bookName yang Anda pinjam telah melewati batas waktu pengembalian. Harap segera dikembalikan."
            : "Ini adalah peringatan terakhir untuk buku $bookName. Jika Anda tidak segera mengembalikannya, denda akan mulai dihitung secara akumulatif!";

        return [
            'title' => $title,
            'message' => $message,
            'loan_id' => $this->loan->id,
            'type' => 'warning'
        ];
    }
}
