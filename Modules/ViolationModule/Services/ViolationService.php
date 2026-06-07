<?php

namespace Modules\ViolationModule\Services;

use Illuminate\Support\Collection;
use Modules\ViolationModule\App\Http\Models\Violation;
use Modules\ViolationModule\Repository\ViolationRepository;

class ViolationService
{
    protected ViolationRepository $violationRepository;

    public function __construct(ViolationRepository $violationRepository)
    {
        $this->violationRepository = $violationRepository;
    }

    public function getAllViolations(): Collection
    {
        return $this->violationRepository->getAllViolations();
    }

    public function findViolation(int $id): Violation
    {
        return $this->violationRepository->findViolation($id);
    }

    public function createViolation(array $data, array $repeats): Violation
    {
        $violation = $this->violationRepository->createViolation([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $this->violationRepository->syncRepeats($violation->id, $repeats);

        return $violation;
    }

    public function updateViolation(int $id, array $data, array $repeats): Violation
    {
        $violation = $this->violationRepository->updateViolation($id, [
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        $this->violationRepository->syncRepeats($violation->id, $repeats);

        return $violation;
    }

    public function deleteViolation(int $id): void
    {
        $this->violationRepository->deleteViolation($id);
    }
}
