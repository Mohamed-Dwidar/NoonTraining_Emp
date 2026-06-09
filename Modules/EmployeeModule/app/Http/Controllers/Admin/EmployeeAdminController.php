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
        $employees   = $this->employeeService->filter($request->all())->paginate(30);
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
                'stu_commission'       => 'nullable|numeric|min:0',
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
                'stu_commission.numeric'        => 'عمولة الطلاب يجب أن تكون رقماً',
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
                'stu_commission'       => 'nullable|numeric|min:0',
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
                'stu_commission.numeric'        => 'عمولة الطلاب يجب أن تكون رقماً',
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

    public function updateStatus(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:active,suspended,on_leave,resigned,terminated',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'حالة غير صحيحة'], 422);
        }

        $employee = $this->employeeService->findOne($id);
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'الموظف غير موجود'], 404);
        }

        $this->employeeService->updateStatus($id, $request->status);

        return response()->json(['success' => true, 'message' => 'تم تحديث الحالة بنجاح']);
    }

    public function updateCommission(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'stu_commission' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'قيمة العمولة غير صحيحة'], 422);
        }

        $employee = $this->employeeService->findOne($id);
        if (!$employee) {
            return response()->json(['success' => false, 'message' => 'الموظف غير موجود'], 404);
        }

        if (!$employee->branch || $employee->branch->type !== 'training') {
            return response()->json(['success' => false, 'message' => 'لا يمكن تعيين عمولة الطلاب إلا لموظفي فروع التدريب'], 422);
        }

        $this->employeeService->updateCommission($id, $request->stu_commission);

        return response()->json(['success' => true, 'message' => 'تم تحديث العمولة بنجاح']);
    }
}
