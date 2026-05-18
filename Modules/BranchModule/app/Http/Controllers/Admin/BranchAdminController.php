<?php

namespace Modules\BranchModule\App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\BranchModule\Services\BranchService;

class BranchAdminController extends Controller {
    protected $branchService;

    public function __construct(BranchService $branchService) {
        $this->branchService = $branchService;
    }

    public function index(Request $request) {
        $branches = $this->branchService->filter($request->all())->get();
        return view('branchmodule::Admin.index', compact('branches'));
    }

    public function show($id) {
        $branch = $this->branchService->findOne($id);
        return view('branchmodule::Admin.show', compact('branch'));
    }


    public function create() {
        return view('branchmodule::Admin.create');
    }

    public function store(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
            ],
            [
                'name.required' => 'اسم الفرع مطلوب',
                'type.required' => 'نوع الفرع مطلوب',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $branch = $this->branchService->create($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.branches.index')
            ->with('success', 'تم اضافة الفرع بنجاح');
    }

    public function edit($id) {
        $branch = $this->branchService->findOne($id);
        return view('branchmodule::Admin.edit', compact('branch'));
    }

    public function update(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'type' => 'required|string|max:255',
            ],
            [
                'name.required' => 'اسم الفرع مطلوب',
                'type.required' => 'نوع الفرع مطلوب',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $branch = $this->branchService->update($request->all());

        return redirect()->route(Auth::getDefaultDriver() . '.branches.index')
            ->with('success', 'تم تعديل الفرع بنجاح');
    }

    public function destroy($id) {
        $this->branchService->deleteBranch($id);
        return redirect()->route('admin.branches.index')->with('success', 'تم حذف الفرع بنجاح');
    }
}
