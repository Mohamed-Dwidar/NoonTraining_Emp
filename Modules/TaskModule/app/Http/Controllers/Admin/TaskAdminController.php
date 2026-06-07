<?php

namespace Modules\TaskModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\TaskModule\App\Http\Models\Task;
use Modules\TaskModule\Services\TaskService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;

class TaskAdminController extends Controller
{
    protected TaskService $taskService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;

    public function __construct(
        TaskService $taskService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService
    ) {
        $this->taskService       = $taskService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
    }

    public function index(Request $request)
    {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();

        $branchId = $request->input('branch_id',     session('task_branch_id', null));
        $deptId   = $request->input('department_id', null);
        $status   = $request->input('status',        null);

        session(['task_branch_id' => $branchId]);

        $tasks    = $this->taskService->getTasks(
            $branchId ? (int) $branchId : null,
            $deptId   ? (int) $deptId   : null,
            $status   ?: null
        );
        $statuses = Task::STATUSES;

        return view('taskmodule::Admin.index', compact(
            'tasks', 'branches', 'departments', 'branchId', 'deptId', 'status', 'statuses'
        ));
    }

    public function create()
    {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();
        $statuses    = Task::STATUSES;

        return view('taskmodule::Admin.create', compact(
            'branches', 'departments', 'employees', 'statuses'
        ));
    }

    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required|exists:employees,id',
                'title'       => 'required|string|max:255',
                'details'     => 'nullable|string',
                'start_date'  => 'required|date',
                'end_date'    => 'required|date|after_or_equal:start_date',
            ],
            [
                'employee_id.required'    => 'الموظف مطلوب',
                'employee_id.exists'      => 'الموظف غير موجود',
                'title.required'          => 'عنوان المهمة مطلوب',
                'start_date.required'     => 'تاريخ البداية مطلوب',
                'end_date.required'       => 'تاريخ النهاية مطلوب',
                'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->taskService->createTask(
            $request->only('employee_id', 'title', 'details', 'start_date', 'end_date', 'status')
        );

        return redirect()->route('admin.tasks.index')->with('success', 'تم إضافة المهمة بنجاح');
    }

    public function edit(int $id)
    {
        $task        = $this->taskService->findTask($id);
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();
        $statuses    = Task::STATUSES;

        return view('taskmodule::Admin.edit', compact(
            'task', 'branches', 'departments', 'employees', 'statuses'
        ));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required|exists:employees,id',
                'title'       => 'required|string|max:255',
                'details'     => 'nullable|string',
                'start_date'  => 'required|date',
                'end_date'    => 'required|date|after_or_equal:start_date',
                'status'      => 'required|in:new,in_progress,completed,late',
            ],
            [
                'employee_id.required'    => 'الموظف مطلوب',
                'employee_id.exists'      => 'الموظف غير موجود',
                'title.required'          => 'عنوان المهمة مطلوب',
                'start_date.required'     => 'تاريخ البداية مطلوب',
                'end_date.required'       => 'تاريخ النهاية مطلوب',
                'end_date.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
                'status.required'         => 'الحالة مطلوبة',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->taskService->updateTask(
            $id,
            $request->only('employee_id', 'title', 'details', 'start_date', 'end_date', 'status')
        );

        return redirect()->route('admin.tasks.index')->with('success', 'تم تحديث المهمة بنجاح');
    }

    public function updateStatus(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:new,in_progress,completed,late',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $this->taskService->updateStatus($id, $request->input('status'));

        return redirect()->back()->with('success', 'تم تحديث حالة المهمة');
    }

    public function destroy(int $id)
    {
        $this->taskService->deleteTask($id);
        return redirect()->route('admin.tasks.index')->with('success', 'تم حذف المهمة بنجاح');
    }
}
