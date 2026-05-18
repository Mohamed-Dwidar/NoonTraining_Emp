<?php

namespace Modules\DepartmentModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\BranchModule\Services\BranchService;
use Modules\DepartmentModule\Services\DepartmentService;

class DepartmentAdminController extends Controller {
    protected $departmentService;
    protected $branchService;

    public function __construct(DepartmentService $departmentService, BranchService $branchService) {
        $this->departmentService = $departmentService;
        $this->branchService = $branchService;
    }

    public function index(Request $request) {
        $departments = $this->departmentService->filter($request->all())->get();
        return view('departmentmodule::Admin.index', compact('departments'));
    }

    public function show($id) {
        $department = $this->departmentService->findOne($id);
        return view('departmentmodule::Admin.show', compact('department'));
    }


    public function create() {
        $branches = $this->branchService->getAllBranches();
        return view('departmentmodule::Admin.create', compact('branches'));
    }

    public function store(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'branch_id' => 'required|exists:branches,id'
            ],
            [
                'name.required' => 'اسم القسم مطلوب',
                'branch_id.required' => 'الفرع مطلوب',
                'branch_id.exists' => 'الفرع غير موجود'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $department = $this->departmentService->create($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.departments.index')
            ->with('success', 'تم اضافة القسم بنجاح');
    }

    public function edit($id) {
        $department = $this->departmentService->findOne($id);
        $branches = $this->branchService->getAllBranches();
        return view('departmentmodule::Admin.edit', compact('department', 'branches'));
    }

    public function update(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'branch_id' => 'required|exists:branches,id'
            ],
            [
                'name.required' => 'اسم القسم مطلوب',
                'branch_id.required' => 'الفرع مطلوب',
                'branch_id.exists' => 'الفرع غير موجود'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $department = $this->departmentService->update($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.departments.index')
            ->with('success', 'تم تعديل القسم بنجاح');
    }

    public function destroy($id) {
        $this->departmentService->deleteDepartment($id);
        return redirect()->route('admin.departments.index')->with('success', 'تم حذف القسم بنجاح');
    }
}
