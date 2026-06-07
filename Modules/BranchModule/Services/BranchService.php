<?php

namespace Modules\BranchModule\Services;

use App\Helpers\UploaderHelper;
use Modules\BranchModule\Repository\BranchRepository;

class BranchService {

    protected $branchRepository;
    use UploaderHelper;

    public function __construct(BranchRepository $branchRepository) {
        $this->branchRepository = $branchRepository;
    }

    public function getAllBranches() {
        return $this->branchRepository->all();
    }

    public function findWhere($arr) {
        return $this->branchRepository->findWhere($arr);
    }

    public function findOne($id) {
        return $this->branchRepository->findWhere(['id' => $id])->first();
    }

    public function getBranchById($id) {
        return $this->branchRepository->find($id);
    }

    public function create($data) {
        $branchData = [
            'name' => $data['name'],
            'type' => $data['type'],
            'address' => $data['address']
        ];

        $branch = $this->branchRepository->create($branchData);
        return $branch;
    }

    public function update($data) {
        $id = $data['id'];
        $branch = $this->branchRepository->find($id);
        if (!$branch) {
            return null; // Handle case where branch is not found
        }

        // Update branch data
        $branchData = [
            'name' => $data['name'] ?? $branch->name,
            'type' => $data['type'] ?? $branch->type,
            'address' => $data['address'] ?? $branch->address
        ];

        $this->branchRepository->update($branchData, $id);

        return $branch;
    }

    public function deleteBranch($id) {
        return $this->branchRepository->delete($id);
    }

    public function updateWorkRegulations(int $id, ?string $content): void
    {
        $this->branchRepository->update(['work_regulations' => $content], $id);
    }

    public function filter($data = []) {
        return $this->branchRepository->filter($data);
    }
}
