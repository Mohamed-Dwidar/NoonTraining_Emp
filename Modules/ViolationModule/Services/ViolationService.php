<?php

namespace Modules\ViolationModule\Services;

use Illuminate\Support\Collection;
use Modules\ViolationModule\App\Http\Models\Violation;
use Modules\ViolationModule\Repository\ViolationRepository;

class ViolationService {
    protected ViolationRepository $violationRepository;

    public function __construct(ViolationRepository $violationRepository) {
        $this->violationRepository = $violationRepository;
    }

    public function findAll() {
        return $this->violationRepository->all();
    }

    public function paginate($perPage = 15) {
        return $this->violationRepository->paginate($perPage);
    }

    public function filter($request = []) {
        return $this->violationRepository->filter($request);
    }

    public function find(int $id): Violation {
        return $this->violationRepository->find($id);
    }

    public function create(array $data, array $repeats): Violation {
        $violation = $this->violationRepository->create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $this->violationRepository->syncRepeats($violation->id, $repeats);

        return $violation;
    }

    public function update(array $data, $id) {
        $updateData = [
            'name'       => $data['name'],
            'description' => $data['description'] ?? null,
        ];
        $violation = $this->violationRepository->update($updateData, $id);

        $repeats = $data['repeats'] ?? [];

        $this->violationRepository->syncRepeats($violation->id, $repeats);

        return $violation;
    }

    public function delete(int $id): void {
        $this->violationRepository->delete($id);
    }
}
