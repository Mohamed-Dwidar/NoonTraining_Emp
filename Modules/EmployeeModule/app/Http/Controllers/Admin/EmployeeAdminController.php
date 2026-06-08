<?php

namespace Modules\EmployeeModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;

class EmployeeAdminController extends Controller {
    protected EmployeeService $employeeService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;

    public function __construct(EmployeeService $employeeService, BranchService $branchService, DepartmentService $departmentService) {
        $this->employeeService   = $employeeService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
    }

    public function index(Request $request) {
        $employees   = $this->employeeService->filter($request->all())->paginate(10);
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        return view('employeemodule::Admin.index', compact('employees', 'branches', 'departments'));
    }

    public function show($id) {
        $employee = $this->employeeService->findOne($id);
        return view('employeemodule::Admin.show', compact('employee'));
    }

    public function create() {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        return view('employeemodule::Admin.create', compact('branches', 'departments'));
    }

    public function store(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'name'                 => 'required|string|max:255',
                'email'                => 'required|email|unique:users,email',
                'job'                  => 'required|string|max:255',
                'branch_id'            => 'required|exists:branches,id',
                'department_id'        => 'required|exists:departments,id',
                'basic_salary'         => 'required|numeric|min:0',
                'monthly_working_days' => 'required|integer|min:1|max:31',
                'daily_working_hours'  => 'required|integer|min:1|max:24',
            ],
            [
                'name.required'                 => 'اسم الموظف مطلوب',
                'email.required'                => 'البريد الإلكتروني مطلوب',
                'email.email'                   => 'البريد الإلكتروني غير صحيح',
                'email.unique'                  => 'البريد الإلكتروني مستخدم من قبل',
                'job.required'                  => 'الوظيفة مطلوبة',
                'branch_id.required'            => 'الفرع مطلوب',
                'branch_id.exists'              => 'الفرع غير موجود',
                'department_id.required'        => 'القسم مطلوب',
                'department_id.exists'          => 'القسم غير موجود',
                'basic_salary.required'         => 'الراتب الأساسي مطلوب',
                'basic_salary.numeric'          => 'الراتب الأساسي يجب أن يكون رقماً',
                'monthly_working_days.required' => 'أيام العمل الشهرية مطلوبة',
                'monthly_working_days.integer'  => 'أيام العمل الشهرية يجب أن تكون رقماً صحيحاً',
                'daily_working_hours.required'  => 'ساعات العمل اليومية مطلوبة',
                'daily_working_hours.integer'   => 'ساعات العمل اليومية يجب أن تكون رقماً صحيحاً',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->employeeService->create($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.employees.index')
            ->with('success', 'تم اضافة الموظف بنجاح');
    }

    public function edit($id) {
        $employee    = $this->employeeService->findOne($id);
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        return view('employeemodule::Admin.edit', compact('employee', 'branches', 'departments'));
    }

    public function update(Request $request) {
        $employeeId = $request->input('id');
        $validator = Validator::make(
            $request->all(),
            [
                'name'                 => 'required|string|max:255',
                'email'                => 'required|email|unique:users,email,' . $employeeId . ',userable_id,userable_type,' . \Modules\EmployeeModule\App\Http\Models\Employee::class,
                'job'                  => 'required|string|max:255',
                'branch_id'            => 'required|exists:branches,id',
                'department_id'        => 'required|exists:departments,id',
                'basic_salary'         => 'required|numeric|min:0',
                'monthly_working_days' => 'required|integer|min:1|max:31',
                'daily_working_hours'  => 'required|integer|min:1|max:24',
            ],
            [
                'name.required'                 => 'اسم الموظف مطلوب',
                'email.required'                => 'البريد الإلكتروني مطلوب',
                'email.email'                   => 'البريد الإلكتروني غير صحيح',
                'email.unique'                  => 'البريد الإلكتروني مستخدم من قبل',
                'job.required'                  => 'الوظيفة مطلوبة',
                'branch_id.required'            => 'الفرع مطلوب',
                'branch_id.exists'              => 'الفرع غير موجود',
                'department_id.required'        => 'القسم مطلوب',
                'department_id.exists'          => 'القسم غير موجود',
                'basic_salary.required'         => 'الراتب الأساسي مطلوب',
                'basic_salary.numeric'          => 'الراتب الأساسي يجب أن يكون رقماً',
                'monthly_working_days.required' => 'أيام العمل الشهرية مطلوبة',
                'monthly_working_days.integer'  => 'أيام العمل الشهرية يجب أن تكون رقماً صحيحاً',
                'daily_working_hours.required'  => 'ساعات العمل اليومية مطلوبة',
                'daily_working_hours.integer'   => 'ساعات العمل اليومية يجب أن تكون رقماً صحيحاً',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->employeeService->update($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.employees.index')
            ->with('success', 'تم تعديل الموظف بنجاح');
    }

    public function destroy($id) {
        $this->employeeService->deleteEmployee($id);
        return redirect()->route('admin.employees.index')->with('success', 'تم حذف الموظف بنجاح');
    }

    public function departmentsByBranch($branchId) {
        $departments = $this->departmentService->findWhere(['branch_id' => $branchId]);
        return response()->json($departments);
    }
}
