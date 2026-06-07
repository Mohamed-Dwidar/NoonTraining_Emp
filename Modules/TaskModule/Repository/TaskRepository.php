<?php

namespace Modules\TaskModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\TaskModule\App\Http\Models\Task;

class TaskRepository extends BaseRepository {
    public function model() {
        return Task::class;
    }

    function filter($request) {
        return Task::filter($request);
    }
}
