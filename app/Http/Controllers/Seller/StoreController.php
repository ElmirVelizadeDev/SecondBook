<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreController extends Controller
{
    /**
     * Show seller store page.
     */
    public function edit()
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect()
                ->route('frontend.home')
                ->with('error', 'Your store could not be found.');
        }

        return view('seller.store', compact('store'));
    }

    /**
     * Update seller store information.
     */
    public function update(Request $request)
    {
        $store = auth()->user()->store;

        if (!$store) {
            return redirect()
                ->route('frontend.home')
                ->with('error', 'Your store could not be found.');
        }

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ]);

        if ($request->hasFile('logo')) {
            if ($store->logo) {
                Storage::disk('public')->delete($store->logo);
            }

            $validated['logo'] = $request->file('logo')
                ->store('stores', 'public');
        }

        $store->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address' => $validated['address'] ?? null,
            'logo' => $validated['logo'] ?? $store->logo,
        ]);

        /*
         * Create seller notification after successful update.
         */
        Notification::create([
            'user_id' => auth()->id(),
            'type' => 'store_updated',
            'title' => 'Store Updated',
            'message' => "\"{$store->name}\" store information has been updated successfully.",
            'read_at' => null,
        ]);

        return redirect()
            ->route('seller.store')
            ->with(
                'success',
                'Store information updated successfully.'
            );
    }
}