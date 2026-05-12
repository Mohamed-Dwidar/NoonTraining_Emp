<?php

namespace Modules\AdminModule\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\ExamModule\Services\ExamService;
use Modules\QuestionModule\Services\QuestionService;
use Modules\StudentModule\Services\StudentExamService;
use Modules\StudentModule\Services\StudentService;

class AdminModuleController extends Controller {

    public function __construct() {
    }

    public function dashboard() {
        $data = [];

        return view('adminmodule::admin.dashboard', $data);
    }
}
