<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentRepositoriesInterface;
use App\Models\Development;
use Exception;
use Illuminate\Support\Facades\DB;

class DevelopmentRepositories implements DevelopmentRepositoriesInterface
{

    public function getAll(?string $search, ?int $limit, bool $execute)
    {
        $query = Development::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->latest()->with('developmentApplicants.user');

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
        $query = Development::where('id', $id)->with('developmentApplicants.user');
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {

            $development = new Development();
            $development->thumbnail = $data['thumbnail']->store('assets/development', 'public');
            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];

            $development->save();
            DB::commit();
            return $development;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();

        try {
            $development = Development::find($id);

            if (isset($data['thumbnail'])) {
                $development->thumbnail = $data['thumbnail']->store('assets/development', 'public');
            }

            $development->name = $data['name'];
            $development->description = $data['description'];
            $development->person_in_charge = $data['person_in_charge'];
            $development->start_date = $data['start_date'];
            $development->end_date = $data['end_date'];
            $development->amount = $data['amount'];
            $development->status = $data['status'];

            $development->save();
            DB::commit();
            return $development;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $development = Development::find($id);
            $development->delete();
            DB::commit();
            return $development;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
