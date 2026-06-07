<?php

namespace Modules\StudentModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\StudentModule\App\Http\Models\Student;

class StudentRepository extends BaseRepository
{
    public function model()
    {
        return Student::class;
    }

    // public function getAll(?int $branchId = null, ?int $deptId = null, ?int $employeeId = null)
    // {
    //     $query = Student::with(['employee.branch', 'employee.department']);

    //     if ($branchId) {
    //         $query->whereHas('employee', fn($q) => $q->where('branch_id', $branchId));
    //     }
    //     if ($deptId) {
    //         $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
    //     }
    //     if ($employeeId) {
    //         $query->where('employee_id', $employeeId);
    //     }

    //     return $query->orderByDesc('id')->get();
    // }

    // public function createStudent(array $data): Student
    // {
    //     return Student::create($data);
    // }

    // public function updateStudent(int $id, array $data): Student
    // {
    //     $student = Student::findOrFail($id);
    //     $student->update($data);
    //     return $student->fresh();
    // }

    // public function deleteStudent(int $id): void
    // {
    //     Student::findOrFail($id)->delete();
    // }

    // public function findStudent(int $id): Student
    // {
    //     return Student::with(['employee.branch', 'employee.department'])->findOrFail($id);
    // }

    function filter($request) {
        return Student::filter($request);
    }
}
