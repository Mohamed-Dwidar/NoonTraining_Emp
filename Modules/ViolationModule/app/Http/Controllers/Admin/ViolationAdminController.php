<?php

namespace Modules\ViolationModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ViolationModule\Services\ViolationService;

class ViolationAdminController extends Controller
{
    protected ViolationService $violationService;

    public function __construct(ViolationService $violationService)
    {
        $this->violationService = $violationService;
    }

    public function index(Request $request)
    {
        $violations = $this->violationService->filter($request->all())->paginate(50);
        return view('violationmodules::Admin.index', compact('violations'));
    }

    public function create()
    {
        return view('violationmodules::Admin.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'                        => 'required|string|max:255|unique:violations,name',
                'description'                 => 'nullable|string',
                'repeats'                     => 'required|array|min:1',
                'repeats.*.repeat_number'     => 'required|integer|min:1',
                'repeats.*.deduction_amount'  => 'required|numeric|min:0',
            ],
            [
                'name.required'                       => 'اسم المخالفة مطلوب',
                'name.unique'                         => 'هذه المخالفة موجودة مسبقاً',
                'repeats.required'                    => 'يجب إضافة تكرار واحد على الأقل',
                'repeats.*.repeat_number.required'    => 'رقم التكرار مطلوب',
                'repeats.*.deduction_amount.required' => 'مبلغ الخصم مطلوب',
                'repeats.*.deduction_amount.numeric'  => 'مبلغ الخصم يجب أن يكون رقماً',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->violationService->create(
            $request->only('name', 'description'),
            $request->input('repeats', [])
        );

        return redirect()->route('admin.violations.index')
            ->with('success', 'تم إضافة المخالفة بنجاح');
    }

    public function edit(int $id)
    {
        $violation = $this->violationService->find($id);
        return view('violationmodules::Admin.edit', compact('violation'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name'                        => 'required|string|max:255|unique:violations,name,' . $id,
                'description'                 => 'nullable|string',
                'repeats'                     => 'required|array|min:1',
                'repeats.*.repeat_number'     => 'required|integer|min:1',
                'repeats.*.deduction_amount'  => 'required|numeric|min:0',
            ],
            [
                'name.required'                       => 'اسم المخالفة مطلوب',
                'name.unique'                         => 'هذه المخالفة موجودة مسبقاً',
                'repeats.required'                    => 'يجب إضافة تكرار واحد على الأقل',
                'repeats.*.repeat_number.required'    => 'رقم التكرار مطلوب',
                'repeats.*.deduction_amount.required' => 'مبلغ الخصم مطلوب',
                'repeats.*.deduction_amount.numeric'  => 'مبلغ الخصم يجب أن يكون رقماً',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $this->violationService->update(
            $request->all(),
            $id
        );

        return redirect()->route('admin.violations.index')
            ->with('success', 'تم تحديث المخالفة بنجاح');
    }

    public function destroy(int $id)
    {
        $this->violationService->delete($id);
        return redirect()->route('admin.violations.index')
            ->with('success', 'تم حذف المخالفة بنجاح');
    }
}
