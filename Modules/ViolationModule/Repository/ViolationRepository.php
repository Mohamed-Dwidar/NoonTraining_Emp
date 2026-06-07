<?php

namespace Modules\ViolationModule\Repository;

use Prettus\Repository\Eloquent\BaseRepository;
use Modules\ViolationModule\App\Http\Models\Violation;
use Modules\ViolationModule\App\Http\Models\ViolationRepeat;

class ViolationRepository extends BaseRepository
{
    public function model()
    {
        return Violation::class;
    }

    public function getAllViolations()
    {
        return Violation::with('repeats')->orderBy('name')->get();
    }

    public function findViolation(int $id): Violation
    {
        return Violation::with('repeats')->findOrFail($id);
    }

    public function createViolation(array $data): Violation
    {
        return Violation::create($data);
    }

    public function syncRepeats(int $violationId, array $repeats): void
    {
        ViolationRepeat::where('violation_id', $violationId)->delete();

        foreach ($repeats as $repeat) {
            ViolationRepeat::create([
                'violation_id'     => $violationId,
                'repeat_number'    => $repeat['repeat_number'],
                'deduction_amount' => $repeat['deduction_amount'],
            ]);
        }
    }

    public function updateViolation(int $id, array $data): Violation
    {
        $violation = Violation::findOrFail($id);
        $violation->update($data);
        return $violation->fresh();
    }

    public function deleteViolation(int $id): void
    {
        Violation::findOrFail($id)->delete();
    }
}
