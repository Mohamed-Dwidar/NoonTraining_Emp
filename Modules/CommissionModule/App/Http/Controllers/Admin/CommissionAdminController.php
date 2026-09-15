<?php

namespace Modules\CommissionModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidatorContract;
use Modules\CommissionModule\Services\CommissionService;

class CommissionAdminController extends Controller {
    protected $commissionService;

    public function __construct(CommissionService $commissionService) {
        $this->commissionService = $commissionService;
    }

    public function index(Request $request) {
        $commissions = $this->commissionService->filter($request->all())->get();
        return view('commissionmodule::Admin.index', compact('commissions'));
    }

    public function create() {
        return view('commissionmodule::Admin.create');
    }

    public function store(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->commissionService->create($request->all());

        return redirect()->route('admin.commissions.index')
            ->with('success', 'تم إضافة العمولة بنجاح');
    }

    public function edit($id) {
        $commission = $this->commissionService->findOne($id);
        return view('commissionmodule::Admin.edit', compact('commission'));
    }

    public function update(Request $request) {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->commissionService->update((int) $request->input('id'), $request->all());

        return redirect()->route('admin.commissions.index')
            ->with('success', 'تم تعديل العمولة بنجاح');
    }

    public function destroy($id) {
        $this->commissionService->deleteCommission($id);
        return redirect()->route('admin.commissions.index')->with('success', 'تم حذف العمولة بنجاح');
    }

    private function validator(array $data): ValidatorContract {
        return Validator::make(
            $data,
            [
                'name'  => 'required|string|max:255',
                'type'  => 'required|in:fixed,percentage',
                'value' => 'required|numeric|min:0',
            ],
            [
                'name.required'  => 'اسم العمولة مطلوب',
                'type.required'  => 'نوع العمولة مطلوب',
                'type.in'        => 'نوع العمولة غير صالح',
                'value.required' => 'قيمة العمولة مطلوبة',
                'value.numeric'  => 'قيمة العمولة يجب أن تكون رقمًا',
            ]
        );
    }
}
