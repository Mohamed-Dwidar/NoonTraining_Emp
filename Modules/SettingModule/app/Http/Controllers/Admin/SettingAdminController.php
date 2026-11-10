<?php

namespace Modules\SettingModule\app\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\SettingModule\app\Services\SettingService;

class SettingAdminController extends Controller
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index()
    {
        $settings = $this->settingService->findAll();
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

        $this->settingService->create((object) [
            'key'   => $request->key,
            'value' => $request->value ?? '0',
            'label' => $request->label ?? '',
        ]);

        return redirect()->route('admin.settings.index')
            ->with('success', __('messages.successfully_saved'));
    }

    public function update(Request $request)
    {
        $this->settingService->updateValues($request->input('settings', []));

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم تعديل الاعدادات بنجاح');
    }
}
