<?php

namespace App\Repositories;

use App\Interfaces\DevelopmentRepositoriesInterface;
use App\Models\Development;
use App\Models\FamilyMember;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DevelopmentRepositories implements DevelopmentRepositoriesInterface
{

    public function getAll(
        ?string $search,
        ?string $status,
        ?int $limit,
        bool $execute
    ) {
        $query = Development::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        })->latest()->with('developmentApplicants.user');


        if ($status === 'my-applications') {
            $query->whereHas('developmentApplicants', function ($query) {
                $members = FamilyMember::where('head_of_family_id', auth()->user()->headOfFamily->id)->pluck('user_id')->toArray();
                $members[] = auth()->user()->id;

                $query->whereIn('user_id', $members);
            });
        }

        if ($limit) {
            $query->limit($limit);
        }

        if ($execute) {
            return $query->get();
        }

        return $query;
    }

    private function deleteFile(?string $path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function getAllPaginated(
        ?string $search,
        ?string $status,
        ?int $rowPerPage
    ) {
        $query = $this->getAll(
            $search,
            $status,
            $rowPerPage,
            false
        );
        return $query->paginate($rowPerPage);
    }

    public function getById(string $id)
    {
        return Development::with('developmentApplicants.user')
            ->find($id);
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
                $this->deleteFile($development->thumbnail);
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
