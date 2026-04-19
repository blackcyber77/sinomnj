<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Expense;
use App\Models\KpiEntry;
use App\Models\ShiftReport;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $user = auth()->user();

        if ($user->isOwner()) {
            $today = Carbon::today();

            return view('dashboard.owner', [
                'staffCount' => User::query()->where('role', 'staff')->count(),
                'todayAttendance' => Attendance::query()->whereDate('attendance_date', $today)->count(),
                'todayRevenue' => ShiftReport::query()->whereDate('report_date', $today)->sum('cash_sales')
                    + ShiftReport::query()->whereDate('report_date', $today)->sum('qris_sales')
                    + ShiftReport::query()->whereDate('report_date', $today)->sum('merchant_sales'),
                'pendingExpenses' => Expense::query()->where('status', 'pending')->count(),
                'pendingKpi' => KpiEntry::query()->where('status', 'pending')->count(),
            ]);
        }

        $todayAttendance = Attendance::query()
            ->where('user_id', $user->id)
            ->whereDate('attendance_date', Carbon::today())
            ->first();

        return view('dashboard.staff', [
            'todayAttendance' => $todayAttendance,
            'weekShifts' => ShiftReport::query()->where('user_id', $user->id)->whereBetween('report_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
            'pendingExpenses' => Expense::query()->where('user_id', $user->id)->where('status', 'pending')->count(),
            'pendingKpi' => KpiEntry::query()->where('user_id', $user->id)->where('status', 'pending')->count(),
        ]);
    }
}
