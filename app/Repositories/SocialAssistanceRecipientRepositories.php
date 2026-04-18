<?php

namespace App\Repositories;

use App\Interfaces\SocialAssistanceRecipientRepositoriesInterface;
use App\Models\SocialAssistanceRecipient;
use Exception;
use Illuminate\Support\Facades\DB;

class SocialAssistanceRecipientRepositories implements SocialAssistanceRecipientRepositoriesInterface
{

    public function getAll(?string $search, ?int $limit, bool $execute)
    {

        $query = SocialAssistanceRecipient::where(
            function ($query) use ($search) {
                if ($search) {
                    $query->search($search);
                }
            }
        )->with('socialAssistance', 'headOfFamily');

        $query->orderBy('created_at', 'desc');

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
        $query = SocialAssistanceRecipient::where('id', $id)->with('socialAssistance', 'headOfFamily');
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipients = new SocialAssistanceRecipient();
            $socialAssistanceRecipients->social_assistance_id = $data['social_assistance_id'];
            $socialAssistanceRecipients->head_of_family_id = $data['head_of_family_id'];
            $socialAssistanceRecipients->amount = $data['amount'];
            $socialAssistanceRecipients->reason = $data['reason'];
            $socialAssistanceRecipients->bank = $data['bank'];
            $socialAssistanceRecipients->account_number = $data['account_number'];

            if (isset($data['proof'])) {
                $socialAssistanceRecipients->proof =
                    $data['proof']->store('assets/social-assistance-recipients', 'public');
            }

            if (isset($data['status'])) {
                $socialAssistanceRecipients->status =
                    $data['status'];
            }

            $socialAssistanceRecipients->save();

            DB::commit();
            return $socialAssistanceRecipients;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $socialAssistanceRecipients = SocialAssistanceRecipient::find($id);

            $socialAssistanceRecipients->amount = $data['amount'];
            $socialAssistanceRecipients->reason = $data['reason'];
            $socialAssistanceRecipients->bank = $data['bank'];
            $socialAssistanceRecipients->account_number = $data['account_number'];

            if (isset($data['proof'])) {
                $socialAssistanceRecipients->proof =
                    $data['proof']->store('assets/social-assistance-recipients', 'public');
            }

            if (isset($data['status'])) {
                $socialAssistanceRecipients->status =
                    $data['status'];
            }

            $socialAssistanceRecipients->save();
            DB::commit();
            return $socialAssistanceRecipients;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();

        try {
            $socialAssistanceRecipients = SocialAssistanceRecipient::find($id);
            $socialAssistanceRecipients->delete();

            DB::commit();
            return $socialAssistanceRecipients;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
