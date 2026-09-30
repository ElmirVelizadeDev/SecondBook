<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponsController extends Controller
{
    private ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    public function index(Request $request)
    {
        $query = Coupon::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(
                'code',
                'like',
                "%{$search}%"
            );
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        // Type Filter
        if ($request->filled('type')) {
            $query->where(
                'type',
                $request->type
            );
        }

        $coupons = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Statistics
        $totalCoupons = Coupon::count();

        $activeCoupons = Coupon::where(
            'status',
            true
        )->count();

        $expiredCoupons = Coupon::where(
            'expires_at',
            '<',
            now()
        )->count();

        $totalUsed = Coupon::sum('used_count');

        return view(
            'admin.coupons.index',
            compact(
                'coupons',
                'totalCoupons',
                'activeCoupons',
                'expiredCoupons',
                'totalUsed'
            )
        );
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:coupons,code',
            ],
            'type' => [
                'required',
                'in:percentage,fixed',
            ],
            'value' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'minimum_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'maximum_discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'starts_at' => [
                'required',
                'date',
            ],
            'expires_at' => [
                'required',
                'date',
                'after:starts_at',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ]);

        // Normalize Code
        $validated['code'] = strtoupper(
            Str::slug(
                $validated['code'],
                ''
            )
        );

        // Percentage Validation
        if (
            $validated['type'] === 'percentage'
            && $validated['value'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'value' =>
                        'Percentage discount cannot be greater than 100%.',
                ]);
        }

        $coupon = Coupon::create($validated);

        $this->activityLogService->log(
            'created',
            'Coupons',
            "Coupon \"{$coupon->code}\" was created."
        );

        return redirect()
            ->route('admin.coupons.index')
            ->with(
                'success',
                'Coupon created successfully.'
            );
    }

    public function show(Coupon $coupon)
    {
        return view(
            'admin.coupons.show',
            compact('coupon')
        );
    }

    public function edit(Coupon $coupon)
    {
        return view(
            'admin.coupons.edit',
            compact('coupon')
        );
    }

    public function update(
        Request $request,
        Coupon $coupon
    ) {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:coupons,code,' . $coupon->id,
            ],
            'type' => [
                'required',
                'in:percentage,fixed',
            ],
            'value' => [
                'required',
                'numeric',
                'min:0.01',
            ],
            'minimum_order_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'maximum_discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'usage_limit' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'starts_at' => [
                'required',
                'date',
            ],
            'expires_at' => [
                'required',
                'date',
                'after:starts_at',
            ],
            'status' => [
                'required',
                'boolean',
            ],
        ]);

        // Normalize Code
        $validated['code'] = strtoupper(
            Str::slug(
                $validated['code'],
                ''
            )
        );

        // Percentage Validation
        if (
            $validated['type'] === 'percentage'
            && $validated['value'] > 100
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'value' =>
                        'Percentage discount cannot be greater than 100%.',
                ]);
        }

        $coupon->update($validated);

        $this->activityLogService->log(
            'updated',
            'Coupons',
            "Coupon \"{$coupon->code}\" was updated."
        );

        return redirect()
            ->route('admin.coupons.index')
            ->with(
                'success',
                'Coupon updated successfully.'
            );
    }

    public function destroy(Coupon $coupon)
    {
        $couponCode = $coupon->code;

        $this->activityLogService->log(
            'deleted',
            'Coupons',
            "Coupon \"{$couponCode}\" was deleted."
        );

        $coupon->delete();

        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully.',
        ]);
    }

    // Toggle Status
    public function toggleStatus(Coupon $coupon)
    {
        $oldStatus = $coupon->status;

        $coupon->update([
            'status' => !$coupon->status,
        ]);

        $newStatus = $coupon->status;

        $this->activityLogService->log(
            'updated',
            'Coupons',
            "Coupon \"{$coupon->code}\" status changed from "
            . ($oldStatus ? 'Active' : 'Inactive')
            . ' to '
            . ($newStatus ? 'Active' : 'Inactive')
            . '.'
        );

        return response()->json([
            'success' => true,
            'status' => (bool) $newStatus,
            'message' => $newStatus
                ? 'Coupon activated successfully.'
                : 'Coupon deactivated successfully.',
        ]);
    }
}

