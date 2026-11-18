<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {

        $fields = [
            'cafe_name',
            'cafe_phone',
            'cafe_address',
            'cafe_hours',
            'about_text',
            'instagram_url', // Tambahan
            'tiktok_url'     // Tambahan
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                Setting::updateOrCreate(
                    ['key' => $field],
                    ['value' => $request->input($field)]
                );
            }
        }

        return redirect()->back()->with('success', 'Pengaturan cafe berhasil diperbarui.');
    }
}
