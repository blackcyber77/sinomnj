<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Expense;
use App\Models\KpiEntry;
use App\Models\KpiVariable;
use App\Models\ShiftReport;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class StaffController extends Controller
{
    public function attendance(): View
    {
        $today = Attendance::query()
            ->where('user_id', auth()->id())
            ->whereDate('attendance_date', today())
            ->first();

        return view('staff.attendance', [
            'today' => $today,
        ]);
    }

    public function checkIn(): RedirectResponse
    {
        $attendance = Attendance::query()->firstOrCreate(
            ['user_id' => auth()->id(), 'attendance_date' => today()->toDateString()],
            ['check_in_at' => now()]
        );

        if (! $attendance->check_in_at) {
            $attendance->update(['check_in_at' => now()]);
        }

        AuditLogger::forModel('attendance.check-in', $attendance);

        return back()->with('success', 'Check-in berhasil.');
    }

    public function checkOut(): RedirectResponse
    {
        $attendance = Attendance::query()->where('user_id', auth()->id())->whereDate('attendance_date', today())->first();

        if (! $attendance || ! $attendance->check_in_at) {
            return back()->withErrors(['attendance' => 'Anda belum check-in hari ini.']);
        }

        $old = $attendance->getOriginal();
        $attendance->update(['check_out_at' => now()]);

        AuditLogger::log('attendance.check-out', 'Attendance', $attendance->id, $old, $attendance->fresh()->getAttributes());

        return back()->with('success', 'Check-out berhasil.');
    }

    public function shifts(): View
    {
        return view('staff.shifts', [
            'reports' => ShiftReport::query()->where('user_id', auth()->id())->latest('report_date')->latest()->limit(30)->get(),
        ]);
    }

    public function shiftStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'report_date' => ['required', 'date'],
            'shift_name' => ['required', 'string', 'max:100'],
            'opening_cash' => ['required', 'numeric', 'min:0'],
            'cash_sales' => ['required', 'numeric', 'min:0'],
            'qris_sales' => ['required', 'numeric', 'min:0'],
            'merchant_sales' => ['required', 'numeric', 'min:0'],
            'closing_cash_actual' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
        ]);

        $report = ShiftReport::query()->create([
            ...$data,
            'user_id' => auth()->id(),
        ]);

        AuditLogger::forModel('shift-report.created', $report);

        return back()->with('success', 'Rekap shift berhasil disimpan.');
    }

    public function expenses(): View
    {
        return view('staff.expenses', [
            'reports' => ShiftReport::query()->where('user_id', auth()->id())->latest('report_date')->limit(50)->get(),
            'expenses' => Expense::query()->where('user_id', auth()->id())->latest()->limit(50)->get(),
        ]);
    }

    public function expenseStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'expense_date' => ['required', 'date'],
            'shift_report_id' => ['nullable', 'exists:shift_reports,id'],
            'category' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string'],
            'receipt' => ['required', 'image', 'max:4096'],
        ]);

        $path = $request->file('receipt')->store('receipts', 'public');

        $expense = Expense::query()->create([
            'user_id' => auth()->id(),
            'shift_report_id' => $data['shift_report_id'] ?? null,
            'expense_date' => $data['expense_date'],
            'category' => $data['category'],
            'amount' => $data['amount'],
            'description' => $data['description'] ?? null,
            'receipt_path' => $path,
            'status' => 'pending',
        ]);

        AuditLogger::forModel('expense.created', $expense);

        return back()->with('success', 'Pengeluaran berhasil diajukan.');
    }

    public function kpi(): View
    {
        return view('staff.kpi', [
            'variables' => KpiVariable::query()->where('is_active', true)->orderBy('name')->get(),
            'entries' => KpiEntry::query()->with('variable')->where('user_id', auth()->id())->latest('entry_date')->latest()->limit(50)->get(),
        ]);
    }

    public function kpiStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'entry_date' => ['required', 'date'],
            'kpi_variable_id' => ['required', 'exists:kpi_variables,id'],
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
        ]);

        $entry = KpiEntry::query()->create([
            ...$data,
            'user_id' => auth()->id(),
            'status' => 'pending',
        ]);

        AuditLogger::forModel('kpi.submitted', $entry);

        return back()->with('success', 'Laporan KPI berhasil dikirim.');
    }
}
