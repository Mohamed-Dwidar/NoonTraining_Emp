<?php

namespace Modules\AdminModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ExamModule\Services\ExamService;
use Modules\QuestionModule\Services\QuestionService;
use Modules\StudentModule\Services\StudentExamService;
use Modules\StudentModule\Services\StudentService;

class AdminModuleController extends Controller {
    private $examService;
    private $studentExamService;
    private $questionService;
    private $studentService;

    public function __construct(StudentExamService $studentExamService, ExamService $examService, QuestionService $questionService, StudentService $studentService) {
        $this->examService = $examService;
        $this->studentExamService = $studentExamService;
        $this->questionService = $questionService;
        $this->studentService = $studentService;
    }

    public function dashboard() {
        $studentExams = $this->studentExamService->findAll();
        $totalQuestions = $this->questionService->findAll()->count();
        $totalStudents = $this->studentService->findAll()->count();

        $data = [
            'totalStudentExams' => $studentExams->count(),
            'totalCompletedExams' => $studentExams->whereNotNull('completed_at')->count(),
            'totalQuestions' => $totalQuestions,
            'totalStudents' => $totalStudents,
        ];

        return view('adminmodule::admin.dashboard', $data);
    }
}
