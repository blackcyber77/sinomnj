<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\KpiEntry;
use App\Models\KpiVariable;
use App\Models\MenuVariant;
use App\Models\Payroll;
use App\Models\PayrollPeriod;
use App\Models\SalesDailySummary;
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
    public function menuVariantsIndex(): View
    {
        return view('owner.menu-variants', [
            'variants' => MenuVariant::query()->orderBy('name')->get(),
        ]);
    }

    public function menuVariantsStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:menu_variants,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $variant = MenuVariant::query()->create([
            'name' => $data['name'],
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLogger::forModel('menu-variant.created', $variant);

        return back()->with('success', 'Varian menu berhasil ditambahkan.');
    }

    public function menuVariantsDelete(MenuVariant $variant): RedirectResponse
    {
        $old = $variant->getAttributes();
        $variant->delete();

        AuditLogger::log('menu-variant.deleted', 'MenuVariant', $variant->id, $old, null);

        return back()->with('success', 'Varian menu berhasil dihapus.');
    }

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

    public function staffUpdateCredentials(Request $request, User $staff): RedirectResponse
    {
        abort_unless($staff->role === 'staff', 404);

        $data = $request->validate([
            'email' => ['required', 'email', 'unique:users,email,'.$staff->id],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $old = $staff->getOriginal();

        $payload = [
            'email' => $data['email'],
        ];

        if (! empty($data['password'])) {
            $payload['password'] = $data['password'];
        }

        $staff->update($payload);

        AuditLogger::log('staff.credentials.updated', 'User', $staff->id, $old, $staff->fresh()->getAttributes());

        return back()->with('success', 'Email/password staff berhasil diperbarui.');
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

    public function salesAnalytics(Request $request): View
    {
        $dateFrom = Carbon::parse($request->input('date_from', now()->startOfMonth()->toDateString()))->startOfDay();
        $dateTo = Carbon::parse($request->input('date_to', now()->toDateString()))->endOfDay();

        if ($dateFrom->gt($dateTo)) {
            [$dateFrom, $dateTo] = [$dateTo->copy()->startOfDay(), $dateFrom->copy()->endOfDay()];
        }

        $groupBy = $request->input('group_by', 'day');
        if (! in_array($groupBy, ['day', 'week', 'month'], true)) {
            $groupBy = 'day';
        }

        $availableMetrics = [
            'total_revenue' => ['label' => 'Total Revenue', 'color' => '#141413'],
            'walk_in_revenue' => ['label' => 'Walk In Revenue', 'color' => '#3860BE'],
            'merchant_revenue' => ['label' => 'Merchant Revenue', 'color' => '#CF4500'],
            'shopee_revenue' => ['label' => 'Shopee Revenue', 'color' => '#9A3A0A'],
            'grab_revenue' => ['label' => 'Grab Revenue', 'color' => '#F37338'],
            'gojek_revenue' => ['label' => 'Gojek Revenue', 'color' => '#696969'],
            'total_qty' => ['label' => 'Total Produk Laku', 'color' => '#EB001B'],
            'merchant_qty' => ['label' => 'Produk Laku Merchant', 'color' => '#F79E1B'],
        ];

        $selectedMetrics = $request->input('metrics', ['total_revenue', 'total_qty']);
        if (! is_array($selectedMetrics)) {
            $selectedMetrics = [$selectedMetrics];
        }

        $selectedMetrics = array_values(array_filter(
            array_unique($selectedMetrics),
            fn ($metric): bool => is_string($metric) && array_key_exists($metric, $availableMetrics)
        ));

        if ($selectedMetrics === []) {
            $selectedMetrics = ['total_revenue', 'total_qty'];
        }

        $summaries = SalesDailySummary::query()
            ->with('items')
            ->whereBetween('report_date', [$dateFrom->toDateString(), $dateTo->toDateString()])
            ->orderBy('report_date')
            ->get();

        $points = $summaries
            ->groupBy(function (SalesDailySummary $summary) use ($groupBy): string {
                return match ($groupBy) {
                    'week' => $summary->report_date->copy()->startOfWeek()->toDateString(),
                    'month' => $summary->report_date->format('Y-m'),
                    default => $summary->report_date->toDateString(),
                };
            })
            ->map(function ($group, string $key) use ($groupBy): array {
                $walkInRevenue = (float) $group->sum('walk_in_revenue');
                $shopeeRevenue = (float) $group->sum('shopee_revenue');
                $grabRevenue = (float) $group->sum('grab_revenue');
                $gojekRevenue = (float) $group->sum('gojek_revenue');
                $merchantRevenue = $shopeeRevenue + $grabRevenue + $gojekRevenue;
                $totalRevenue = $walkInRevenue + $merchantRevenue;

                $items = $group->flatMap(fn (SalesDailySummary $summary) => $summary->items);
                $totalQty = (int) $items->sum('quantity');
                $merchantQty = (int) $items
                    ->filter(fn ($item): bool => in_array($item->channel, ['shopee', 'grab', 'gojek'], true))
                    ->sum('quantity');

                $label = match ($groupBy) {
                    'week' => 'Minggu '.$group->first()->report_date->copy()->startOfWeek()->format('d M Y'),
                    'month' => Carbon::createFromFormat('Y-m', $key)->translatedFormat('M Y'),
                    default => $group->first()->report_date->format('d M Y'),
                };

                return [
                    'sort_key' => $key,
                    'label' => $label,
                    'total_revenue' => round($totalRevenue, 2),
                    'walk_in_revenue' => round($walkInRevenue, 2),
                    'merchant_revenue' => round($merchantRevenue, 2),
                    'shopee_revenue' => round($shopeeRevenue, 2),
                    'grab_revenue' => round($grabRevenue, 2),
                    'gojek_revenue' => round($gojekRevenue, 2),
                    'total_qty' => $totalQty,
                    'merchant_qty' => $merchantQty,
                ];
            })
            ->sortBy('sort_key')
            ->values();

        $datasets = [];
        foreach ($selectedMetrics as $metric) {
            $config = $availableMetrics[$metric];

            $datasets[] = [
                'label' => $config['label'],
                'data' => $points->pluck($metric)->values(),
                'borderColor' => $config['color'],
                'backgroundColor' => $config['color'],
                'tension' => 0.35,
                'fill' => false,
                'borderWidth' => 2.5,
            ];
        }

        $allItems = $summaries->flatMap(fn (SalesDailySummary $summary) => $summary->items);
        $totalQtyAll = (int) $allItems->sum('quantity');

        $distinctDays = max(1, $summaries->pluck('report_date')->map(fn ($date) => $date->toDateString())->unique()->count());
        $distinctWeeks = max(1, $summaries->pluck('report_date')->map(fn ($date) => $date->copy()->startOfWeek()->toDateString())->unique()->count());
        $distinctMonths = max(1, $summaries->pluck('report_date')->map(fn ($date) => $date->format('Y-m'))->unique()->count());
        $customRangeSpanDays = max(1, $dateFrom->diffInDays($dateTo) + 1);

        $avgStats = [
            'avg_day' => round($totalQtyAll / $distinctDays, 2),
            'avg_week' => round($totalQtyAll / $distinctWeeks, 2),
            'avg_month' => round($totalQtyAll / $distinctMonths, 2),
            'avg_custom_range' => round($totalQtyAll / $customRangeSpanDays, 2),
        ];

        $topProducts = $allItems
            ->groupBy('product_name')
            ->map(fn ($items, $name): array => [
                'name' => $name,
                'qty' => (int) $items->sum('quantity'),
                'revenue' => (float) $items->sum('revenue'),
            ])
            ->sortByDesc('qty')
            ->take(10)
            ->values();

        return view('owner.sales-analytics', [
            'dateFrom' => $dateFrom->toDateString(),
            'dateTo' => $dateTo->toDateString(),
            'groupBy' => $groupBy,
            'availableMetrics' => $availableMetrics,
            'selectedMetrics' => $selectedMetrics,
            'chartPayload' => [
                'labels' => $points->pluck('label')->values(),
                'datasets' => $datasets,
            ],
            'points' => $points,
            'avgStats' => $avgStats,
            'topProducts' => $topProducts,
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
