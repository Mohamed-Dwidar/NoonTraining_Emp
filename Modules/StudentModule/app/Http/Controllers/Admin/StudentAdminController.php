<?php

namespace Modules\StudentModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\StudentModule\Services\StudentService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\StudentModule\App\Imports\StudentImport;

class StudentAdminController extends Controller {
    protected StudentService $studentService;
    protected BranchService $branchService;
    protected DepartmentService $departmentService;
    protected EmployeeService $employeeService;

    public function __construct(
        StudentService $studentService,
        BranchService $branchService,
        DepartmentService $departmentService,
        EmployeeService $employeeService
    ) {
        $this->studentService    = $studentService;
        $this->branchService     = $branchService;
        $this->departmentService = $departmentService;
        $this->employeeService   = $employeeService;
    }

    public function index(Request $request) {
        $branches    = $this->branchService->getAllBranches()->where('type', 'training');
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        $branchId   = $request->input('branch_id',     session('student_branch_id', null));
        $deptId     = $request->input('department_id', null);
        $employeeId = $request->input('employee_id',   null);

        session(['student_branch_id' => $branchId]);

        $students = $this->studentService->filter($request->all())->paginate(50);

        return view('studentmodule::Admin.index', compact(
            'students',
            'branches',
            'departments',
            'employees',
            'branchId',
            'deptId',
            'employeeId'
        ));
    }

    public function create() {
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        return view('studentmodule::Admin.create', compact(
            'branches',
            'departments',
            'employees'
        ));
    }

    public function store(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id'          => 'required|exists:employees,id',
                'name'                 => 'required|string|max:255',
                'mobile'               => 'required|string|max:20',
                'course_name'          => 'required|string|max:255',
                'total_amount'         => 'required|integer|min:0',
                'paid_amount'          => 'required|integer|min:0',
                'payment_method'       => 'required|string|max:50',
                'payment_date'         => 'required|date',
                'previous_student_of'  => 'nullable|string|max:255',
            ],
            [
                'employee_id.required'    => 'الموظف المسؤول مطلوب',
                'employee_id.exists'      => 'الموظف غير موجود',
                'name.required'           => 'اسم الطالب مطلوب',
                'mobile.required'         => 'رقم الجوال مطلوب',
                'course_name.required'    => 'اسم الكورس مطلوب',
                'total_amount.required'   => 'المبلغ الإجمالي مطلوب',
                'paid_amount.required'    => 'المبلغ المدفوع مطلوب',
                'payment_method.required' => 'طريقة الدفع مطلوبة',
                'payment_date.required'   => 'تاريخ الدفع مطلوب',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->studentService->create(
            $request->only(
                'employee_id',
                'name',
                'mobile',
                'course_name',
                'total_amount',
                'paid_amount',
                'payment_method',
                'payment_date',
                'previous_student_of'
            )
        );

        return redirect()->route('admin.students.index')->with('success', 'تم إضافة الطالب بنجاح');
    }

    public function edit(int $id) {
        $student     = $this->studentService->findStudent($id);
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        return view('studentmodule::Admin.edit', compact(
            'student',
            'branches',
            'departments',
            'employees'
        ));
    }

    public function update(Request $request, int $id) {
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id'          => 'required|exists:employees,id',
                'name'                 => 'required|string|max:255',
                'mobile'               => 'required|string|max:20',
                'course_name'          => 'required|string|max:255',
                'total_amount'         => 'required|integer|min:0',
                'paid_amount'          => 'required|integer|min:0',
                'payment_method'       => 'required|string|max:50',
                'payment_date'         => 'required|date',
                'previous_student_of'  => 'nullable|string|max:255',
            ],
            [
                'employee_id.required'    => 'الموظف المسؤول مطلوب',
                'employee_id.exists'      => 'الموظف غير موجود',
                'name.required'           => 'اسم الطالب مطلوب',
                'mobile.required'         => 'رقم الجوال مطلوب',
                'course_name.required'    => 'اسم الكورس مطلوب',
                'total_amount.required'   => 'المبلغ الإجمالي مطلوب',
                'paid_amount.required'    => 'المبلغ المدفوع مطلوب',
                'payment_method.required' => 'طريقة الدفع مطلوبة',
                'payment_date.required'   => 'تاريخ الدفع مطلوب',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $this->studentService->update(
            $id,
            $request->only(
                'employee_id',
                'name',
                'mobile',
                'course_name',
                'total_amount',
                'paid_amount',
                'payment_method',
                'payment_date',
                'previous_student_of'
            )
        );

        return redirect()->route('admin.students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function destroy(int $id) {
        $this->studentService->delete($id);
        return redirect()->route('admin.students.index')->with('success', 'تم حذف الطالب بنجاح');
    }

    public function importStudents(Request $request) {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'file'        => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(
            new StudentImport($this->studentService, (int) $request->input('employee_id')),
            $request->file('file')
        );

        return back()->with('success', 'تم الاستيراد بنجاح!');
    }
}
