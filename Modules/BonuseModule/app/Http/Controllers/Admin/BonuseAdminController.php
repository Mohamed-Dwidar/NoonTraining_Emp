<?php

namespace Modules\BonuseModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\BonuseModule\Services\BonuseService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;

class BonuseAdminController extends Controller
{
    protected BonuseService $bonuseService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;

    public function __construct(
        BonuseService $bonuseService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService
    ) {
        $this->bonuseService     = $bonuseService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
    }

    public function index(Request $request)
    {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();

        $month    = $request->input('month',         session('bonuse_month',     now()->format('Y-m')));
        $branchId = $request->input('branch_id',     session('bonuse_branch_id', null));
        $deptId   = $request->input('department_id', null);

        session(['bonuse_month' => $month, 'bonuse_branch_id' => $branchId]);

        $bonuses = $this->bonuseService->filter([
            'month'         => $month,
            'branch_id'     => $branchId,
            'department_id' => $deptId,
        ])->paginate(15);

        return view('bonusemodule::Admin.index', compact(
            'bonuses', 'branches', 'departments', 'month', 'branchId', 'deptId'
        ));
    }

    public function create(Request $request)
    {
        $month       = $request->input('month', session('bonuse_month', now()->format('Y-m')));
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        return view('bonusemodule::Admin.create', compact(
            'month', 'branches', 'departments', 'employees'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required|exists:employees,id',
                'month'       => 'required|string|size:7',
                'amount'      => 'required|numeric|min:0',
                'reason'      => 'required|string',
            ],
            [
                'employee_id.required' => 'الموظف مطلوب',
                'employee_id.exists'   => 'الموظف غير موجود',
                'month.required'       => 'الشهر مطلوب',
                'month.size'           => 'صيغة الشهر غير صحيحة',
                'amount.required'      => 'مبلغ المكافأة مطلوب',
                'amount.numeric'       => 'مبلغ المكافأة يجب أن يكون رقماً',
                'amount.min'           => 'مبلغ المكافأة يجب أن يكون أكبر من صفر',
                'reason.required'      => 'السبب مطلوب',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->bonuseService->create(
            $request->only('employee_id', 'month', 'amount', 'reason')
        );

        return redirect()->route('admin.bonuses.index', ['month' => $request->month])
            ->with('success', 'تم إضافة المكافأة بنجاح');
    }

    public function edit(int $id)
    {
        $bonuse      = $this->bonuseService->find($id);
        $month       = $bonuse->month;
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        return view('bonusemodule::Admin.edit', compact(
            'bonuse', 'month', 'branches', 'departments', 'employees'
        ));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required|exists:employees,id',
                'month'       => 'required|string|size:7',
                'amount'      => 'required|numeric|min:0',
                'reason'      => 'required|string',
            ],
            [
                'employee_id.required' => 'الموظف مطلوب',
                'employee_id.exists'   => 'الموظف غير موجود',
                'month.required'       => 'الشهر مطلوب',
                'month.size'           => 'صيغة الشهر غير صحيحة',
                'amount.required'      => 'مبلغ المكافأة مطلوب',
                'amount.numeric'       => 'مبلغ المكافأة يجب أن يكون رقماً',
                'amount.min'           => 'مبلغ المكافأة يجب أن يكون أكبر من صفر',
                'reason.required'      => 'السبب مطلوب',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->bonuseService->update(
            $request->only('employee_id', 'month', 'amount', 'reason') + ['id' => $id]
        );

        return redirect()->route('admin.bonuses.index', ['month' => $request->month])
            ->with('success', 'تم تحديث المكافأة بنجاح');
    }

    public function destroy(int $id)
    {
        $bonuse = $this->bonuseService->find($id);
        $month  = $bonuse->month;
        $this->bonuseService->delete($id);

        return redirect()->route('admin.bonuses.index', ['month' => $month])
            ->with('success', 'تم حذف المكافأة بنجاح');
    }
}
