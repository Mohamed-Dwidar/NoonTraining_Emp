<?php

namespace Modules\UserModule\App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Modules\ExamModule\Services\ExamService;
use Modules\QuestionModule\Services\QuestionService;
use Modules\StudentModule\Services\StudentExamService;
use Modules\StudentModule\Services\StudentService;
use Modules\UserModule\Services\UserService;

class UserAuthController extends Controller {
    private $userService;
    private $studentExamService;
    private $questionService;
    private $studentService;

    public function __construct(UserService $userService, StudentExamService $studentExamService, ExamService $examService, QuestionService $questionService, StudentService $studentService) {
        $this->userService = $userService;
        $this->studentExamService = $studentExamService;
        $this->questionService = $questionService;
        $this->studentService = $studentService;
    }

    public function dashboard() {
        if (Auth::guard('user')->check()) {
            $studentExams = $this->studentExamService->findAll();
            $totalQuestions = $this->questionService->findAll()->count();
            $totalStudents = $this->studentService->findAll()->count();

            $data = [
                'totalStudentExams' => $studentExams->count(),
                'totalCompletedExams' => $studentExams->whereNotNull('completed_at')->count(),
                'totalQuestions' => $totalQuestions,
                'totalStudents' => $totalStudents,
            ];

            return view('usermodule::user.dashboard', $data);
        } else {
            return redirect('user/login');
        }
    }

    public function loginForm() {
        if (Auth::guard('user')->check()) {
            return redirect()->route('user.dashboard');
        } else {
            return view('usermodule::login');
        }
    }

    public function login(Request $request) {
        $rememberme = $request->has('rememberme') ? true : false;

        if (auth('user')->attempt(
            [
                'email' => $request->email,
                'password' => $request->password
            ],
            $rememberme
        )) {
            return redirect()->intended('user/dashboard');
        }

        return redirect()->back()->withErrors(['error' => 'البريد الأليكتروني او كلمة المرور غير صحيحة']);
    }

    public function changePassword() {
        $user = auth()->guard('user')->user();
        return view('usermodule::user.change_password', compact('user'));
    }

    public function updatePassword(Request $request) {
        $validator = Validator::make(
            $request->all(),
            [
                'old_password' => 'required',
                'password' => 'required|confirmed|min:4',
            ]
        );

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = auth()->guard('user')->user();
        $request['id'] = $user->id;

        if (Hash::check($request->old_password, $user->password)) {
            $this->userService->updatePassword($request);

            return redirect()->route(Auth::getDefaultDriver() . '.changePassword')
                ->with('success', 'تم تغيير كلمة المرور بنجاح');
        } else {
            return back()
                ->withErrors(['كلمة المرور القديمة غير صحيحة'])
                ->withInput();
        }
    }

    public function logout(Request $request) {
        Auth::guard('user')->logout();
        $request->session()->flush();
        $request->session()->regenerate();

        return redirect()->to('user');
    }
}
