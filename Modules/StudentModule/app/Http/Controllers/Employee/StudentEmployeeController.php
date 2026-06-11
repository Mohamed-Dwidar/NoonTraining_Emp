<?php

namespace Modules\StudentModule\App\Http\Controllers\Employee;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\StudentModule\Services\StudentService;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;
use Modules\EmployeeModule\Services\EmployeeService;
use Maatwebsite\Excel\Facades\Excel;
use Modules\StudentModule\App\Imports\StudentImport;
use Modules\StudentModule\Exports\StudentsExport;

class StudentEmployeeController extends Controller {
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
        $employee   = auth()->guard('employee')->user();
        $branches    = $this->branchService->getAllBranches()->where('type', 'training');
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        $branchId   =  $employee->userable->branch_id; //$request->input('branch_id',     session('student_branch_id', null));
        $deptId     = $employee->userable->department_id; //$request->input('department_id', null);
        $employeeId = $employee->userable_id; //$request->input('employee_id',   null);

        $request->merge([
            'branch_id'     => $branchId,
            'department_id' => $deptId,
            'employee_id'   => $employeeId,
            'month'         => $request->input('month', now()->format('Y-m')),
        ]);
// dd($request->all());
        $students_query = $this->studentService->filter($request->all());

        if ($request->export == 'yes') {
            $label = '';

            if ($branchId) {
                $label = $label  . ' فرع ' . ($this->branchService->findOne($branchId)->name) . ' - ';
            }
            if ($deptId) {
                $label =  $label . ' قسم ' . ($this->departmentService->findOne($deptId)->name) . ' - ';
            }
            if ($employeeId != null) {
                $label = $label . ' موظف ' . ($this->employeeService->findOne($employeeId)->name) . ' - ';
            }

            $fileName = 'طلاب - ' . $label . now()->format('Y-m-d') . '.xlsx';
            return Excel::download(new StudentsExport($students_query->get()), $fileName);
        }

        $students = $students_query->paginate(50);

        return view('studentmodule::Employee.index', compact(
            'students',
            'branches',
            'departments',
            'employees',
            'branchId',
            'deptId',
            'employeeId'
        ));
    }

    public function importStudents(Request $request) {
        $employee_id = auth()->guard('employee')->user()->userable_id;
        $request->validate([
            //'employee_id' => 'required|exists:employees,id',
            'file'        => 'required|file|mimes:xlsx,xls,csv',
        ]);

        Excel::import(
            new StudentImport($this->studentService, $employee_id),
            $request->file('file')
        );

        return back()->with('success', 'تم الاستيراد بنجاح!');
    }

    public function edit(int $id) {
        $student     = $this->studentService->findStudent($id);
        $branches    = $this->branchService->getAllBranches();
        $departments = $this->departmentService->getAllDepartments();
        $employees   = $this->employeeService->getAllEmployees();

        return view('studentmodule::Employee.edit', compact(
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

        return redirect()->route('employee.students.index')->with('success', 'تم تحديث بيانات الطالب بنجاح');
    }

    public function destroy(int $id) {
        $this->studentService->delete($id);
        return redirect()->route('employee.students.index')->with('success', 'تم حذف الطالب بنجاح');
    }
}
