<?php

namespace Modules\SettingModule\app\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SettingModule\app\Models\Setting;

class SettingAdminController extends Controller
{
    public function index()
    {
        $settings = Setting::get();
        return view('settingmodule::admin.index', compact('settings'));
    }

    public function create()
    {
        return view('settingmodule::admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key'   => 'required|string|unique:settings,key',
            'value' => 'nullable|string',
            'label' => 'nullable|string',
        ]);

        Setting::create([
            'key'   => $request->key,
            'value' => $request->value ?? '0',
            'label' => $request->label ?? '',
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', __('messages.successfully_saved'));
    }

    public function update(Request $request)
    {
        $values = $request->input('settings', []);
        foreach ($values as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم تعديل الاعدادات بنجاح');
    }
}
