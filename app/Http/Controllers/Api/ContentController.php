<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Faq;
use App\Models\Publisher;
use App\Models\Setting;
use App\Models\Shipping;

class ContentController extends Controller
{
    public function siteSettings()
    {
        $keys = [
            'site_name',
            'site_description',
            'support_email',
            'support_phone',
            'address',
            'city',
            'country',
            'logo',
            'facebook',
            'instagram',
            'tiktok',
            'youtube',
            'whatsapp',
            'meta_title',
            'meta_description',
            'keywords',
            'open_graph_image',
            'search_engine_indexing',
            'google_analytics_id',
            'google_search_console_verification',
            'favicon',
        ];

        $data = [];
        foreach ($keys as $key) {
            $data[$key] = Setting::get($key);
        }

        return response()->json(['data' => $data]);
    }

    public function banners()
    {
        return response()->json([
            'data' => Banner::where('status', 'active')
                ->where(fn ($query) => $query->whereNull('start_date')->orWhereDate('start_date', '<=', today()))
                ->where(fn ($query) => $query->whereNull('end_date')->orWhereDate('end_date', '>=', today()))
                ->orderBy('position')
                ->get([
                    'id',
                    'title',
                    'subtitle',
                    'image',
                    'button_text',
                    'button_url',
                    'position',
                ]),
        ]);
    }

    public function faqs()
    {
        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get(['id', 'category', 'question', 'answer']);

        return response()->json([
            'data' => $faqs,
            'categories' => $faqs->pluck('category')->filter()->unique()->values(),
        ]);
    }

    public function publishers()
    {
        return response()->json([
            'data' => Publisher::where('status', true)
                ->orderBy('name')
                ->get(['id', 'name', 'logo', 'country', 'website', 'description']),
        ]);
    }

    public function shipping()
    {
        $enabled = (bool) Setting::get('shipping_enabled', true);

        return response()->json([
            'enabled' => $enabled,
            'default_country' => Setting::get('default_country'),
            'estimated_delivery_message' => Setting::get('estimated_delivery_message', ''),
            'default_shipping_fee' => (float) Setting::get('default_shipping_fee', 0),
            'free_shipping_threshold' => (float) Setting::get('free_shipping_threshold', 0),
            'options' => $enabled
                ? Shipping::where('status', true)
                    ->orderBy('price')
                    ->orderBy('name')
                    ->get(['id', 'name', 'description', 'price', 'delivery_time'])
                : [],
        ]);
    }
}
