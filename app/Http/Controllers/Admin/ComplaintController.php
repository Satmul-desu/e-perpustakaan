<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class ComplaintController extends Controller
{
    public function index(Request $request)
    {
        $query = Complaint::with(['user', 'responder'])
            ->orderByRaw("
                CASE 
                    WHEN priority = 'urgent' THEN 1
                    WHEN priority = 'high' THEN 2
                    WHEN priority = 'normal' THEN 3
                    ELSE 4
                END
            ")
            ->orderBy('created_at', 'desc');
        if ($request->status && $request->status !== 'all') {
            $query->where('status', $request->status);
        if ($request->type && $request->type !== 'all') {
            $query->where('type', $request->type);
        }
        if ($request->priority && $request->priority !== 'all') {
            $query->where('priority', $request->priority);
        }
        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhere('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        $complaints = $query->paginate(15)->withQueryString();
        $stats = [
            'pending' => Complaint::where('status', 'pending')->count(),
            'in_progress' => Complaint::where('status', 'in_progress')->count(),
            'resolved' => Complaint::where('status', 'resolved')->count(),
            'urgent' => Complaint::where('priority', 'urgent')->where('status', '!=', 'resolved')->count(),
        ];
        return view('admin.complaints.index', compact('complaints', 'stats'));
    }
    public function show(Complaint $complaint)
    {
        $complaint->load(['user', 'responder']);
        return view('admin.complaints.show', compact('complaint'));
    }
    public function update(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
            'priority' => 'nullable|in:low,normal,high,urgent',
            'admin_response' => 'required_if:status,resolved,closed|string|min:10',
        ]);
        try {
            DB::beginTransaction();
            $updateData = [
                'status' => $validated['status'],
                'admin_response' => $validated['admin_response'] ?? $complaint->admin_response,
                'responded_by' => Auth::id(),
                'responded_at' => now(),
            ];
            if (isset($validated['priority'])) {
                $updateData['priority'] = $validated['priority'];
            }
            $complaint->update($updateData);
            DB::commit();
            return redirect()
                ->route('admin.complaints.show', $complaint)
                ->with('success', 'Aduan berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->route('admin.complaints.show', $complaint)
                ->with('error', 'Gagal memperbarui aduan. Silakan coba lagi.')
                ->withInput();
        }
    }
    public function quickUpdate(Request $request, Complaint $complaint)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,resolved,closed',
        ]);
        $complaint->update([
            'status' => $validated['status'],
            'responded_by' => Auth::id(),
            'responded_at' => now(),
        ]);
        return redirect()
            ->route('admin.complaints.index')
            ->with('success', 'Status aduan diperbarui.');
    }
    public function destroy(Complaint $complaint)
    {
        $complaint->delete();
        return redirect()
            ->route('admin.complaints.index')
            ->with('success', 'Aduan berhasil dihapus.');
    }
}