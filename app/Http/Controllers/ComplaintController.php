<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use App\Services\ComplaintService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    protected $complaintService;

    public function __construct(ComplaintService $complaintService)
    {
        $this->complaintService = $complaintService;
    }

    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cs.index', compact('complaints'));
    }

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
            $this->complaintService->createComplaint(Auth::user(), $validated);

            return redirect()
                ->route('cs.index')
                ->with('success', 'Aduan/Laporan Anda telah dikirim. Tim kami akan segera meninjaunya.');
        } catch (\Exception $e) {
            return redirect()
                ->route('cs.index')
                ->with('error', 'Gagal mengirim aduan. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function show(Complaint $complaint)
    {
        if ($complaint->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        $complaint->load(['user', 'responder']);

        return view('cs.show', compact('complaint'));
    }
}
