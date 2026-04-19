<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\KpiEntry;
use App\Models\KpiVariable;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Models\ShiftReport;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class OwnerController extends Controller
{
    public function staffIndex(): View
    {
        return view('owner.staff', [
            'staffs' => User::query()->where('role', 'staff')->orderBy('name')->get(),
        ]);
    }

    public function staffStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $staff = User::query()->create([
            ...$data,
            'role' => 'staff',
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLogger::forModel('staff.created', $staff);

        return back()->with('success', 'Staff berhasil ditambahkan.');
    }

    public function staffUpdate(Request $request, User $staff): RedirectResponse
    {
        abort_unless($staff->role === 'staff', 404);

        $old = $staff->getOriginal();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,'.$staff->id],
            'daily_rate' => ['required', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $staff->update([
            ...$data,
            'is_active' => $request->boolean('is_active'),
        ]);

        AuditLogger::log('staff.updated', 'User', $staff->id, $old, $staff->fresh()->getAttributes());

        return back()->with('success', 'Staff berhasil diperbarui.');
    }

    public function staffDelete(User $staff): RedirectResponse
    {
        abort_unless($staff->role === 'staff', 404);

        $old = $staff->getAttributes();
        $staff->delete();

        AuditLogger::log('staff.deleted', 'User', $staff->id, $old, null);

        return back()->with('success', 'Staff berhasil dihapus.');
    }

    public function kpiIndex(): View
    {
        return view('owner.kpi', [
            'variables' => KpiVariable::query()->orderByDesc('is_active')->orderBy('name')->get(),
        ]);
    }

    public function kpiStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $variable = KpiVariable::query()->create([
            ...$data,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLogger::forModel('kpi-variable.created', $variable);

        return back()->with('success', 'Variabel KPI berhasil dibuat.');
    }

    public function kpiUpdate(Request $request, KpiVariable $variable): RedirectResponse
    {
        $old = $variable->getOriginal();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'weight' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $variable->update([
            ...$data,
            'is_active' => $request->boolean('is_active'),
        ]);

        AuditLogger::log('kpi-variable.updated', 'KpiVariable', $variable->id, $old, $variable->fresh()->getAttributes());

        return back()->with('success', 'Variabel KPI berhasil diperbarui.');
    }

    public function validations(): View
    {
        return view('owner.validations', [
            'expenses' => Expense::query()->with('user')->latest()->where('status', 'pending')->get(),
            'kpiEntries' => KpiEntry::query()->with(['user', 'variable'])->latest()->where('status', 'pending')->get(),
        ]);
    }

    public function validateExpense(Request $request, Expense $expense): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
            'validation_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $old = $expense->getOriginal();

        $expense->update([
            'status' => $data['status'],
            'validation_note' => $data['validation_note'] ?? null,
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);

        AuditLogger::log('expense.validated', 'Expense', $expense->id, $old, $expense->fresh()->getAttributes());

        return back()->with('success', 'Validasi pengeluaran tersimpan.');
    }

    public function validateKpi(Request $request, KpiEntry $entry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:approved,rejected'],
        ]);

        $old = $entry->getOriginal();

        $entry->update([
            'status' => $data['status'],
            'validated_by' => auth()->id(),
            'validated_at' => now(),
        ]);

        AuditLogger::log('kpi.validated', 'KpiEntry', $entry->id, $old, $entry->fresh()->getAttributes());

        return back()->with('success', 'Validasi KPI tersimpan.');
    }

    public function financeReport(Request $request): View
    {
        $dateFrom = Carbon::parse($request->input('date_from', now()->startOfMonth()->toDateString()))->startOfDay();
        $dateTo = Carbon::parse($request->input('date_to', now()->toDateString()))->endOfDay();
        $shift = $request->input('shift_name');

        $query = ShiftReport::query()->with('user')->whereBetween('report_date', [$dateFrom, $dateTo]);

        if ($shift) {
            $query->where('shift_name', $shift);
        }

        $reports = $query->latest('report_date')->get();

        $totals = [
            'cash' => $reports->sum('cash_sales'),
            'qris' => $reports->sum('qris_sales'),
            'merchant' => $reports->sum('merchant_sales'),
            'expenses' => Expense::query()->whereBetween('expense_date', [$dateFrom, $dateTo])->sum('amount'),
        ];

        return view('owner.finance', [
            'reports' => $reports,
            'totals' => $totals,
            'dateFrom' => $dateFrom->toDateString(),
            'dateTo' => $dateTo->toDateString(),
            'shiftName' => $shift,
        ]);
    }

    public function payrollIndex(): View
    {
        return view('owner.payroll', [
            'periods' => PayrollPeriod::query()->with('payrolls.user')->latest()->get(),
        ]);
    }

    public function payrollGenerate(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'bonus_threshold' => ['required', 'integer', 'min:1'],
            'bonus_amount' => ['required', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($data): void {
            $period = PayrollPeriod::query()->create([
                ...$data,
                'status' => 'finalized',
                'created_by' => auth()->id(),
            ]);

            $staffs = User::query()->where('role', 'staff')->where('is_active', true)->get();

            foreach ($staffs as $staff) {
                $attendanceDays = $staff->attendances()
                    ->whereBetween('attendance_date', [$data['start_date'], $data['end_date']])
                    ->whereNotNull('check_in_at')
                    ->count();

                $kpiScore = (int) $staff->kpiEntries()
                    ->where('status', 'approved')
                    ->whereBetween('entry_date', [$data['start_date'], $data['end_date']])
                    ->join('kpi_variables', 'kpi_entries.kpi_variable_id', '=', 'kpi_variables.id')
                    ->sum(DB::raw('kpi_entries.score * kpi_variables.weight'));

                $baseSalary = $attendanceDays * (float) $staff->daily_rate;
                $bonus = $kpiScore >= (int) $data['bonus_threshold'] ? (float) $data['bonus_amount'] : 0;
                $total = $baseSalary + $bonus;

                Payroll::query()->create([
                    'payroll_period_id' => $period->id,
                    'user_id' => $staff->id,
                    'attendance_days' => $attendanceDays,
                    'daily_rate' => $staff->daily_rate,
                    'base_salary' => $baseSalary,
                    'kpi_score' => $kpiScore,
                    'bonus_amount' => $bonus,
                    'total_salary' => $total,
                ]);
            }

            AuditLogger::forModel('payroll.generated', $period);
        });

        return back()->with('success', 'Payroll berhasil digenerate.');
    }
}
