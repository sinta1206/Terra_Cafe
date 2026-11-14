<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        // Mengambil semua setting dalam bentuk key => value agar mudah dipanggil di view
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Menangkap field sesuai input di desain pengaturan admin
        $fields = [
            'cafe_name',
            'cafe_phone',
            'cafe_address',
            'operational_hours',
            'delivery_fee'
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
