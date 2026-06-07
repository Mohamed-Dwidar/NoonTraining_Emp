<?php

namespace Modules\TaskModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\TaskModule\App\Http\Models\Task;

class TaskRepository extends BaseRepository
{
    public function model()
    {
        return Task::class;
    }

    public function getAll(?int $branchId = null, ?int $deptId = null, ?string $status = null)
    {
        $query = Task::with(['employee.branch', 'employee.department']);

        if ($branchId) {
            $query->whereHas('employee', fn($q) => $q->where('branch_id', $branchId));
        }
        if ($deptId) {
            $query->whereHas('employee', fn($q) => $q->where('department_id', $deptId));
        }
        if ($status) {
            $query->where('status', $status);
        }

        return $query->orderByDesc('start_date')->get();
    }

    public function createTask(array $data): Task
    {
        return Task::create($data);
    }

    public function updateTask(int $id, array $data): Task
    {
        $task = Task::findOrFail($id);
        $task->update($data);
        return $task->fresh();
    }

    public function updateStatus(int $id, string $status): void
    {
        Task::findOrFail($id)->update(['status' => $status]);
    }

    public function deleteTask(int $id): void
    {
        Task::findOrFail($id)->delete();
    }

    public function findTask(int $id): Task
    {
        return Task::with(['employee.branch', 'employee.department'])->findOrFail($id);
    }
}
