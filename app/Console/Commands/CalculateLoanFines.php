<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Models\User;
use App\Notifications\LoanOverdueNotification;
use App\Notifications\LoanWarningNotification;
use Illuminate\Console\Command;

class CalculateLoanFines extends Command
{
    protected $signature = 'loans:calculate-fines';
    protected $description = 'Hitung denda keterlambatan buku dan kirim peringatan bertahap';

    public function handle()
    {
        $overdueLoans = Loan::whereIn('status', ['borrowed', 'overdue'])
            ->whereNotNull('due_date')
            ->where('due_date', '<', now())
            ->get();

        $count = 0;
        $admins = User::where('role', 'admin')->get();

        foreach ($overdueLoans as $loan) {
            // Simulasi: 1 menit dihitung sebagai 1 hari untuk memudahkan testing.
            $daysLate = max(0, now()->diffInMinutes($loan->due_date));
            
            if ($daysLate >= 1) {
                // Set Overdue jika belum
                if ($loan->status !== 'overdue') {
                    $loan->status = 'overdue';
                    $loan->fine_status = 'unpaid';
                    
                    // Notifikasi pertama kali terlambat
                    if ($loan->user) $loan->user->notify(new LoanOverdueNotification($loan, false));
                    foreach ($admins as $admin) $admin->notify(new LoanOverdueNotification($loan, true));
                }

                $fineAmount = $daysLate * 2000;
                if ($loan->fine_amount != $fineAmount) {
                    $loan->fine_amount = $fineAmount;
                }
                
                $loan->save();
                $count++;
            }

        }
        $this->info("Berhasil memproses $count data peringatan/denda.");
    }
}
