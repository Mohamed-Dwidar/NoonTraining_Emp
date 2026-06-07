<?php

namespace Modules\TaskModule\Services;

use Illuminate\Support\Collection;
use Modules\TaskModule\Repository\TaskRepository;

class TaskService {

    protected TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository) {
        $this->taskRepository = $taskRepository;
    }

    public function getTasks(?int $branchId = null, ?int $deptId = null, ?string $status = null): Collection {
        return $this->taskRepository->getAll($branchId, $deptId, $status);
    }

    public function findTask(int $id) {
        return $this->taskRepository->findTask($id);
    }

    public function createTask(array $data) {
        return $this->taskRepository->createTask($data);
    }

    public function updateTask(int $id, array $data) {
        return $this->taskRepository->updateTask($id, $data);
    }

    public function updateStatus(int $id, string $status): void {
        $this->taskRepository->updateStatus($id, $status);
    }

    public function deleteTask(int $id): void {
        $this->taskRepository->deleteTask($id);
    }
}
