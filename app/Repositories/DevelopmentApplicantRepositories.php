<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentApplicantRepositoriesInterface;
use App\Models\DevelopmentApplicant;
use Exception;
use Illuminate\Support\Facades\DB;

class DevelopmentApplicantRepositories implements DevelopmentApplicantRepositoriesInterface
{
    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = DevelopmentApplicant::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->with('development', 'user');

        if ($limit) {
            $query->limit($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    public function getAllPaginated(
        ?string $search,
        ?int $rowPerPage
    ) {
        $query = $this->getAll($search, $rowPerPage, false);
        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        $query = DevelopmentApplicant::where('id', $id);
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $developmentApplicants = new DevelopmentApplicant();
            $developmentApplicants->development_id = $data['development_id'];
            $developmentApplicants->user_id = $data['user_id'];

            if (isset($data['status'])) {
                $developmentApplicants->status = $data['status'];
            }

            $developmentApplicants->save();
            DB::commit();
            return $developmentApplicants->fresh();
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $developmentApplicants = DevelopmentApplicant::find($id);
            $developmentApplicants->development_id = $data['development_id'];
            $developmentApplicants->user_id = $data['user_id'];

            if (isset($data['status'])) {
                $developmentApplicants->status = $data['status'];
            }

            $developmentApplicants->save();
            DB::commit();
            return $developmentApplicants;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $developmentApplicant = DevelopmentApplicant::find($id);
            $developmentApplicant->delete();
            DB::commit();
            return $developmentApplicant;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
