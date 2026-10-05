<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class StoreSettingsController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect()
                ->route('frontend.home')
                ->with('error', 'Your store could not be found.');
        }

        return view('seller.settings.index', compact('store'));
    }

    public function update(Request $request)
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect()
                ->route('frontend.home')
                ->with('error', 'Your store could not be found.');
        }

        $validated = $request->validate([
            'processing_time' => [
                'required',
                'integer',
                'min:1',
                'max:30',
            ],
            'minimum_order_amount' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
            'order_note' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'accept_orders' => [
                'nullable',
                'boolean',
            ],
            'auto_approve_orders' => [
                'nullable',
                'boolean',
            ],
        ]);

        $changes = [];

        $oldAcceptOrders = (bool) $store->accept_orders;
        $newAcceptOrders = $request->boolean('accept_orders');

        if ($oldAcceptOrders !== $newAcceptOrders) {
            $changes[] = 'Accept Orders ' .
                ($newAcceptOrders ? 'activated' : 'deactivated');
        }

        $oldAutoApprove = (bool) $store->auto_approve_orders;
        $newAutoApprove = $request->boolean('auto_approve_orders');

        if ($oldAutoApprove !== $newAutoApprove) {
            $changes[] = 'Auto Approve Orders ' .
                ($newAutoApprove ? 'activated' : 'deactivated');
        }

        $oldProcessingTime = (int) $store->processing_time;
        $newProcessingTime = (int) $validated['processing_time'];

        if ($oldProcessingTime !== $newProcessingTime) {
            $changes[] = 'Processing Time changed from ' .
                $oldProcessingTime . ' to ' .
                $newProcessingTime . ' days';
        }

        $oldMinimumAmount = (float) $store->minimum_order_amount;
        $newMinimumAmount = (float) $validated['minimum_order_amount'];

        if ($oldMinimumAmount != $newMinimumAmount) {
            $changes[] = 'Minimum Order Amount changed from ' .
                number_format($oldMinimumAmount, 2) . ' to ' .
                number_format($newMinimumAmount, 2);
        }

        $oldOrderNote = $store->order_note ?? '';
        $newOrderNote = $validated['order_note'] ?? '';

        if ($oldOrderNote !== $newOrderNote) {
            $changes[] = 'Order Note updated';
        }

        $store->update([
            'accept_orders' => $newAcceptOrders,
            'auto_approve_orders' => $newAutoApprove,
            'processing_time' => $newProcessingTime,
            'minimum_order_amount' => $newMinimumAmount,
            'order_note' => $newOrderNote ?: null,
        ]);

        if (!empty($changes)) {
            $this->notifyAdmins(
                'store_settings_updated',
                'Store Settings Updated',
                'Seller "' . $this->sellerName() . '" changed: ' .
                implode(', ', $changes) . '.'
            );
        }

        return redirect()
            ->route('seller.settings')
            ->with('success', 'Store settings updated successfully.');
    }

    private function sellerName(): string
    {
        $seller = auth()->user();

        return $seller?->full_name
            ?: $seller?->name
            ?: 'Seller';
    }

    private function notifyAdmins(
        string $type,
        string $title,
        string $message
    ): void {
        Notification::create([
            'user_id' => null,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'read_at' => null,
        ]);
    }
}

