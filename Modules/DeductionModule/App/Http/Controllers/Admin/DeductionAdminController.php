<?php

namespace Modules\DeductionModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\DeductionModule\Services\DeductionService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;
use Modules\ViolationModule\Services\ViolationService;
use Modules\ViolationModule\App\Http\Models\Violation;

class DeductionAdminController extends Controller
{
    protected DeductionService $deductionService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;
    protected ViolationService $violationService;

    public function __construct(
        DeductionService $deductionService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService,
        ViolationService $violationService
    ) {
        $this->deductionService  = $deductionService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
        $this->violationService  = $violationService;
    }

    public function index(Request $request)
    {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();

        $month    = $request->input('month',         session('deduction_month',     now()->format('Y-m')));
        $branchId = $request->input('branch_id',     session('deduction_branch_id', null));
        $deptId   = $request->input('department_id', null);

        session(['deduction_month' => $month, 'deduction_branch_id' => $branchId]);

        $deductions = $this->deductionService->filter([
            'month'         => $month,
            'branch_id'     => $branchId,
            'department_id' => $deptId,
        ])->paginate(15);

        return view('deductionmodules::Admin.index', compact(
            'deductions', 'branches', 'departments', 'month', 'branchId', 'deptId'
        ));
    }

    public function create(Request $request)
    {
        $month       = $request->input('month', session('deduction_month', now()->format('Y-m')));
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();
        $violations  = $this->violationService->filter()->get();

        return view('deductionmodules::Admin.create', compact(
            'month', 'branches', 'departments', 'employees', 'violations'
        ));
    }

    public function store(Request $request)
    {
        $type = $request->input('type', 'custom');

        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|string|size:7',
            'type'        => 'required|in:custom,violation',
            'amount'      => 'required|numeric|min:0',
            'reason'      => $type === 'custom' ? 'required|string' : 'nullable|string',
        ];

        if ($type === 'violation') {
            $rules['violation_id'] = 'required|exists:violations,id';
        }

        $messages = [
            'employee_id.required'  => 'الموظف مطلوب',
            'employee_id.exists'    => 'الموظف غير موجود',
            'month.required'        => 'الشهر مطلوب',
            'month.size'            => 'صيغة الشهر غير صحيحة',
            'type.required'         => 'نوع الخصم مطلوب',
            'type.in'               => 'نوع الخصم غير صحيح',
            'amount.required'       => 'مبلغ الخصم مطلوب',
            'amount.numeric'        => 'مبلغ الخصم يجب أن يكون رقماً',
            'amount.min'            => 'مبلغ الخصم يجب أن يكون أكبر من صفر',
            'reason.required'       => 'السبب مطلوب',
            'violation_id.required' => 'المخالفة مطلوبة',
            'violation_id.exists'   => 'المخالفة غير موجودة',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($type === 'violation') {
            $resolved = $this->deductionService->resolveViolationDeduction(
                (int) $request->employee_id,
                (int) $request->violation_id,
                $request->month
            );
            $data = [
                'employee_id'             => $request->employee_id,
                'month'                   => $request->month,
                'type'                    => 'violation',
                'violation_id'            => $request->violation_id,
                'violation_repeat_number' => $resolved['repeat_number'],
                'amount'                  => $request->amount,
                'reason'                  => $request->reason,
            ];
        } else {
            $data = [
                'employee_id'             => $request->employee_id,
                'month'                   => $request->month,
                'type'                    => 'custom',
                'violation_id'            => null,
                'violation_repeat_number' => null,
                'amount'                  => $request->amount,
                'reason'                  => $request->reason,
            ];
        }
        $this->deductionService->create($data);

        return redirect()->route('admin.deductions.index', ['month' => $request->month])
            ->with('success', 'تم إضافة الخصم بنجاح');
    }

    public function edit(int $id)
    {
        $deduction   = $this->deductionService->find($id);
        $month       = $deduction->month;
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();
        $violations  = $this->violationService->filter()->get();

        return view('deductionmodules::Admin.edit', compact(
            'deduction', 'month', 'branches', 'departments', 'employees', 'violations'
        ));
    }

    public function update(Request $request)
    {
        $type = $request->input('type', 'custom');

        $rules = [
            'employee_id' => 'required|exists:employees,id',
            'month'       => 'required|string|size:7',
            'type'        => 'required|in:custom,violation',
            'amount'      => 'required|numeric|min:0',
            'reason'      => $type === 'custom' ? 'required|string' : 'nullable|string',
        ];

        if ($type === 'violation') {
            $rules['violation_id'] = 'required|exists:violations,id';
        }

        $messages = [
            'employee_id.required'  => 'الموظف مطلوب',
            'employee_id.exists'    => 'الموظف غير موجود',
            'month.required'        => 'الشهر مطلوب',
            'month.size'            => 'صيغة الشهر غير صحيحة',
            'type.required'         => 'نوع الخصم مطلوب',
            'type.in'               => 'نوع الخصم غير صحيح',
            'amount.required'       => 'مبلغ الخصم مطلوب',
            'amount.numeric'        => 'مبلغ الخصم يجب أن يكون رقماً',
            'amount.min'            => 'مبلغ الخصم يجب أن يكون أكبر من صفر',
            'reason.required'       => 'السبب مطلوب',
            'violation_id.required' => 'المخالفة مطلوبة',
            'violation_id.exists'   => 'المخالفة غير موجودة',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($type === 'violation') {
            $resolved = $this->deductionService->resolveViolationDeduction(
                (int) $request->employee_id,
                (int) $request->violation_id,
                $request->month,
                (int) $request->input('id')
            );
            $data = [
                'id'                      => $request->id,
                'employee_id'             => $request->employee_id,
                'month'                   => $request->month,
                'type'                    => 'violation',
                'violation_id'            => $request->violation_id,
                'violation_repeat_number' => $resolved['repeat_number'],
                'amount'                  => $request->amount,
                'reason'                  => $request->reason,
            ];
        } else {
            $data = [
                'id'                      => $request->id,
                'employee_id'             => $request->employee_id,
                'month'                   => $request->month,
                'type'                    => 'custom',
                'violation_id'            => null,
                'violation_repeat_number' => null,
                'amount'                  => $request->amount,
                'reason'                  => $request->reason,
            ];
        }

        $this->deductionService->update($data);

        return redirect()->route('admin.deductions.index', ['month' => $request->month])
            ->with('success', 'تم تحديث الخصم بنجاح');
    }

    public function destroy(int $id)
    {
        $deduction = $this->deductionService->find($id);
        $month     = $deduction->month;
        $this->deductionService->delete($id);

        return redirect()->route('admin.deductions.index', ['month' => $month])
            ->with('success', 'تم حذف الخصم بنجاح');
    }

    public function violationAmount(Request $request)
    {
        $employeeId  = (int) $request->input('employee_id', 0);
        $violationId = (int) $request->input('violation_id', 0);
        $month       = $request->input('month', now()->format('Y-m'));
        $excludeId   = $request->input('exclude_id') ? (int) $request->input('exclude_id') : null;

        $violation = $violationId ? Violation::find($violationId) : null;

        if (!$violationId || !$violation) {
            return response()->json(['repeat_number' => 1, 'amount' => 0, 'violation_name' => '']);
        }

        if (!$employeeId) {
            // No employee yet — return tier 1 amount as preview
            $tier = \Modules\ViolationModule\App\Http\Models\ViolationRepeat::where('violation_id', $violationId)
                ->orderBy('repeat_number')
                ->first();
            return response()->json([
                'repeat_number'  => 1,
                'amount'         => $tier?->deduction_amount ?? 0,
                'violation_name' => $violation->name,
            ]);
        }

        $result = $this->deductionService->resolveViolationDeduction($employeeId, $violationId, $month, $excludeId);

        return response()->json([
            'repeat_number'  => $result['repeat_number'],
            'amount'         => $result['amount'],
            'violation_name' => $violation->name,
        ]);
    }
}
