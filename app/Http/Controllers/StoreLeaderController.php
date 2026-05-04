<?php

namespace App\Http\Controllers;

use App\Models\MenuVariant;
use App\Models\SalesDailySummary;
use App\Models\SalesProductItem;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class StoreLeaderController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = Carbon::parse($request->input('date', today()->toDateString()))->toDateString();

        $summary = SalesDailySummary::query()
            ->with(['items.menuVariant'])
            ->where('user_id', auth()->id())
            ->whereDate('report_date', $selectedDate)
            ->first();

        $menuVariants = MenuVariant::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $quantitiesByVariant = [];
        if ($summary) {
            foreach ($summary->items as $item) {
                if ($item->menu_variant_id) {
                    $quantitiesByVariant[$item->menu_variant_id] = (int) $item->quantity;
                }
            }
        }

        return view('staff.store-leader', [
            'selectedDate' => $selectedDate,
            'summary' => $summary,
            'menuVariants' => $menuVariants,
            'quantitiesByVariant' => $quantitiesByVariant,
        ]);
    }

    public function quantitiesUpsert(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'report_date' => ['required', 'date'],
            'quantities' => ['required', 'array'],
            'quantities.*' => ['nullable', 'integer', 'min:0'],
        ]);

        $variantIds = MenuVariant::query()->where('is_active', true)->pluck('id')->map(fn ($id) => (string) $id)->all();
        $submitted = $data['quantities'];

        foreach (array_keys($submitted) as $variantId) {
            if (! in_array((string) $variantId, $variantIds, true)) {
                abort(422, 'Varian menu tidak valid.');
            }
        }

        $summary = SalesDailySummary::query()->firstOrCreate(
            [
                'user_id' => auth()->id(),
                'report_date' => $data['report_date'],
            ],
            [
                'walk_in_revenue' => 0,
                'shopee_revenue' => 0,
                'grab_revenue' => 0,
                'gojek_revenue' => 0,
            ]
        );

        $variants = MenuVariant::query()->whereIn('id', array_keys($submitted))->get()->keyBy('id');

        foreach ($submitted as $variantId => $qtyRaw) {
            $qty = (int) ($qtyRaw ?? 0);
            $variant = $variants->get((int) $variantId);

            if (! $variant) {
                continue;
            }

            $existing = SalesProductItem::query()
                ->where('sales_daily_summary_id', $summary->id)
                ->where('menu_variant_id', $variant->id)
                ->where('channel', 'walk_in')
                ->first();

            if ($qty <= 0) {
                if ($existing) {
                    $old = $existing->getAttributes();
                    $existing->delete();
                    AuditLogger::log('store-leader-qty.deleted', 'SalesProductItem', $existing->id, $old, null);
                }

                continue;
            }

            if ($existing) {
                $old = $existing->getOriginal();
                $existing->update([
                    'quantity' => $qty,
                    'product_name' => $variant->name,
                    'revenue' => 0,
                ]);
                AuditLogger::log('store-leader-qty.updated', 'SalesProductItem', $existing->id, $old, $existing->fresh()->getAttributes());
            } else {
                $item = $summary->items()->create([
                    'menu_variant_id' => $variant->id,
                    'channel' => 'walk_in',
                    'product_name' => $variant->name,
                    'quantity' => $qty,
                    'revenue' => 0,
                ]);
                AuditLogger::forModel('store-leader-qty.created', $item);
            }
        }

        AuditLogger::forModel('store-leader-qty.bulk-saved', $summary);

        return redirect()
            ->route('staff.store-leader', ['date' => $data['report_date']])
            ->with('success', 'Jumlah laku menu per tanggal berhasil disimpan.');
    }

    public function itemDelete(SalesProductItem $item): RedirectResponse
    {
        abort_unless($item->summary && $item->summary->user_id === auth()->id(), 403);

        $old = $item->getAttributes();
        $item->delete();

        AuditLogger::log('store-leader-item.deleted', 'SalesProductItem', $item->id, $old, null);

        return back()->with('success', 'Item produk berhasil dihapus.');
    }
}
