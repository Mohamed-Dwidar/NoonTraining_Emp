<?php

namespace Modules\StudentModule\Services;

use App\Helpers\GmailSenderHelper;
use Illuminate\Support\Collection;
use Modules\StudentModule\Repository\StudentRepository;

class StudentService {
    use GmailSenderHelper;

    // Who gets an email when an employee imports a students file
    private const IMPORT_ALERT_EMAILS = ['naif@noontraining.com', 'mdwidar84@gmail.com'];

    protected StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository) {
        $this->studentRepository = $studentRepository;
    }

    public function find($id) {
        return $this->studentRepository->find($id);
    }

    /** Employee portal: the student only if it is the employee's own (null otherwise). */
    public function findForEmployee(int $id, int $employeeId) {
        return $this->studentRepository->findForEmployee($id, $employeeId);
    }

    public function findStudent(int $id) {
        return $this->studentRepository->with(['employee.branch', 'employee.department'])->find($id);
    }

    public function paginate($perPage = 15) {
        return $this->studentRepository->paginate($perPage);
    }

    public function filter($request = []) {
        return $this->studentRepository->filter($request);
    }

    public function findAll() {
        return $this->studentRepository->all();
    }

    public function create(array $data) {
        return $this->studentRepository->create([
            'employee_id' => $data['employee_id'],
            'month'       => $data['month'] ?? now()->format('Y-m'),
            'name'        => $data['name'],
            'mobile'      => $data['mobile'],
            'national_id' => $data['national_id'] ?? null,
            'course_name' => $data['course_name'],
            'total_amount' => $data['total_amount'] ?? 0,
            'paid_amount' => $data['paid_amount'] ?? 0,
            'payment_method' => $data['payment_method'] ?? null,
            'payment_date' => $data['payment_date'] ?? null,
            'previous_student_of' => $data['previous_student_of'] ?? null,
            'commission_id' => $data['commission_id'] ?? null,
        ]);
    }

    public function update(int $id, array $data) {
        $updateData = [
            'employee_id' => $data['employee_id'],
            'name'        => $data['name'],
            'mobile'      => $data['mobile'],
            'national_id' => $data['national_id'] ?? null,
            'course_name' => $data['course_name'],
            'total_amount' => $data['total_amount'] ?? 0,
            'paid_amount' => $data['paid_amount'] ?? 0,
            'payment_method' => $data['payment_method'] ?? null,
            'payment_date' => $data['payment_date'] ?? null,
            'previous_student_of' => $data['previous_student_of'] ?? null,
        ];

        if (isset($data['month'])) {
            $updateData['month'] = $data['month'];
        }

        return $this->studentRepository->update($updateData, $id);
    }

    public function delete(int $id): void {
        $this->studentRepository->delete($id);
    }

    /**
     * $employeeId given → only that employee's students are deleted (employee portal).
     * Payroll is recalculated on view, so no recalc is needed here (same as delete()).
     */
    public function deleteMany(array $ids, ?int $employeeId = null): int {
        return $this->studentRepository->deleteByIds($ids, $employeeId);
    }

    public function duplicateNationalIdCounts(): Collection {
        return $this->studentRepository->duplicateNationalIdCounts();
    }

    /**
     * Email the owner that an employee imported students. Called after the rows are saved, so a mail
     * failure is only logged — an error page would make the employee upload (and duplicate) the file again.
     */
    public function sendImportAlert(string $employeeName, int $count, string $month): void {
        try {
            $subject = 'قام ' . $employeeName . ' بإضافة ملف طلاب';

            $html = view('studentmodule::Employee.import_alert_email_temp', [
                'msg_subject' => $subject,
                'msg_body'    => 'قام الموظف ' . $employeeName . ' بأضافة ملف طلاب. عدد الطلاب: ' . $count . '، شهر المحاسبة: ' . $month . '.',
                'msg_footer'  => 'هذه رسالة تلقائية من نظام الموارد البشرية - معهد نون للتدريب',
            ])->render();

            $this->sendMail(self::IMPORT_ALERT_EMAILS, $subject, $html);
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
