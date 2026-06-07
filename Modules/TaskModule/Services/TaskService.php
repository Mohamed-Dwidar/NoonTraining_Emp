<?php

namespace Modules\TaskModule\Services;

use Illuminate\Support\Collection;
use Modules\TaskModule\Repository\TaskRepository;

class TaskService {

    protected TaskRepository $taskRepository;

    public function __construct(TaskRepository $taskRepository) {
        $this->taskRepository = $taskRepository;
    }

    public function find($id) {
        return $this->taskRepository->findWhere(['id' => $id])->first();
    }

    public function create($data) {
        return $this->taskRepository->create([
            'employee_id' => $data['employee_id'],
            'title'       => $data['title'],
            'details'     => $data['details'] ?? null,
            'status'      => $data['status'] ?? 'new',
            'start_date'  => $data['start_date'] ?? null,
            'end_date'    => $data['end_date'] ?? null,
        ]);
    }

    public function update($data) {
        $updateData = [
            'employee_id' => $data['employee_id'],
            'title'       => $data['title'],
            'details'     => $data['details'] ?? null,
            'status'      => $data['status'] ?? 'new',
            'start_date'  => $data['start_date'] ?? null,
            'end_date'    => $data['end_date'] ?? null,
        ];

        return $this->taskRepository->update(
             $updateData,
             $data['id']
         );
    }


    public function updateStatus($id, $status) {
        return $this->taskRepository->update(
             ['status' => $status],
             $id
         );
    }

    public function delete($id) {
        return $this->taskRepository->delete($id);
    }

    public function filter($data = []) {
        return $this->taskRepository->filter($data);
    }
}
