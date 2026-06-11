<?php

namespace Modules\StudentModule\Services;

use Illuminate\Support\Collection;
use Modules\StudentModule\Repository\StudentRepository;

class StudentService {

    protected StudentRepository $studentRepository;

    public function __construct(StudentRepository $studentRepository) {
        $this->studentRepository = $studentRepository;
    }

    public function find($id) {
        return $this->studentRepository->find($id);
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
            'month'       => $data['month'],
            'name'        => $data['name'],
            'mobile'      => $data['mobile'],
            'course_name' => $data['course_name'],
            'total_amount' => $data['total_amount'] ?? 0,
            'paid_amount' => $data['paid_amount'] ?? 0,
            'payment_method' => $data['payment_method'] ?? null,
            'payment_date' => $data['payment_date'] ?? null,
            'previous_student_of' => $data['previous_student_of'] ?? null,
        ]);
    }

    public function update(array $data) {
        $updateData = [
            'employee_id' => $data['employee_id'],
            'month'       => $data['month'],
            'name'        => $data['name'],
            'mobile'      => $data['mobile'],
            'course_name' => $data['course_name'],
            'total_amount' => $data['total_amount'] ?? 0,
            'paid_amount' => $data['paid_amount'] ?? 0,
            'payment_method' => $data['payment_method'] ?? null,
            'payment_date' => $data['payment_date'] ?? null,
            'previous_student_of' => $data['previous_student_of'] ?? null,
        ];

        return $this->studentRepository->update($data['id'], $updateData);
    }

    public function delete(int $id): void {
        $this->studentRepository->delete($id);
    }
}
