<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Notifications\NewComplaintNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ComplaintController extends Controller
{
    /**
     * Display CS page for users.
     */
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cs.index', compact('complaints'));
    }

    /**
     * Store a new complaint/report.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:complaint,report,question',
            'category' => 'required|in:order,product,payment,shipping,other',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|min:10',
            'order_number' => 'nullable|string|max:50',
            'priority' => 'nullable|in:low,normal,high,urgent',
        ]);

        try {
            DB::beginTransaction();

            // Create the complaint first
            $complaint = Complaint::create([
                'user_id' => Auth::id(),
                'type' => $validated['type'],
                'category' => $validated['category'],
                'subject' => $validated['subject'],
                'message' => $validated['message'],
                'order_number' => $validated['order_number'] ?? null,
                'priority' => $validated['priority'] ?? 'normal',
                'status' => 'pending',
            ]);

            DB::commit();

            // Send notification AFTER commit, separate try-catch
            // This ensures complaint is saved even if notification fails
            try {
                $complaint->load('user');
                $admins = \App\Models\User::where('is_admin', true)->get();
                if ($admins->isNotEmpty()) {
                    Notification::send($admins, new NewComplaintNotification($complaint));
                }
            } catch (\Exception $notificationException) {
                // Log notification error but don't fail the submission
                \Log::error('Failed to send complaint notification: ' . $notificationException->getMessage());
            }

            return redirect()
                ->route('cs.index')
                ->with('success', 'Aduan/Laporan Anda telah dikirim. Tim kami akan segera meninjaunya.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Complaint submission failed: ' . $e->getMessage());
            return redirect()
                ->route('cs.index')
                ->with('error', 'Gagal mengirim aduan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Show single complaint details for user.
     */
    public function show(Complaint $complaint)
    {
        // Ensure user can only see their own complaints
        if ($complaint->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $complaint->load(['user', 'responder']);

        return view('cs.show', compact('complaint'));
    }
}

