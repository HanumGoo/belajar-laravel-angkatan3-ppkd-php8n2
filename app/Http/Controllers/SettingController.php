<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $title = "Settings";

        $settings = Setting::pluck('value', 'key')->toArray();

        return view('setting.index', compact('title', 'settings'));
    }
    public function update(Request $request)
    {
        $request->validate([
        'app_name' => 'required|string|max:255',
        'app_email' => 'nullable|email',
        'app_phone' => 'nullable|string|max:50',
        'app_address' => 'nullable|string',
    ]);

    $settings = [
            'app_name' => $request->app_name,
            'app_email' => $request->app_email,
            'app_phone' => $request->app_phone,
            'app_address' => $request->app_address,
        ];

    foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

    Setting::updateOrCreate(
        ['key' => 'app_address'],
        ['value' => $request->app_address]
    );

    return redirect()
        ->route('setting')
        ->with('success', 'Settings updated successfully.');
    }
}
