<?php

namespace App\Repositories;

use App\Interfaces\EventParticipantRepositoriesInterface;
use App\Models\Event;
use App\Models\EventParticipant;
use Exception;
use Illuminate\Support\Facades\DB;

class EventParticipantRepositories implements EventParticipantRepositoriesInterface
{
    public function getAll(
        ?string $search,
        ?int $limit,
        bool $execute
    ) {
        $query = EventParticipant::where(function ($query) use ($search) {
            if ($search) {
                $query->search($search);
            }
        });

        $query->orderBy('created_at', 'desc');

        if (auth()->user()->hasRole('head-of-family')) {
            $headOfFamily = auth()->user()->headOfFamily;

            if ($headOfFamily) {
                $query->where('head_of_family_id', $headOfFamily->id);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

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
        $query = EventParticipant::where('id', $id);
        return $query->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();
        try {

            $event = Event::where('id', $data['event_id'])->first();

            $eventParticipant = new EventParticipant();
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];
            $eventParticipant->quantity = $data['quantity'];
            $eventParticipant->total_price = $event->price * $data['quantity'];
            $eventParticipant->payment_status = "pending";
            $eventParticipant->save();
            DB::commit();

            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = config('midtrans.serverKey');
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = config('midtrans.isProduction');
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = config('midtrans.isSanitized');
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = config('midtrans.is3ds');

            $params = array(
                'transaction_details' => array(
                    'order_id' => $eventParticipant->id,
                    'gross_amount' => $eventParticipant->total_price,
                ),
                'customer_details' => array(
                    'first_name' => auth()->user()->name,
                ),
            );

            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $eventParticipant->snap_token = $snapToken;

            return $eventParticipant;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(string $id, array $data)
    {
        DB::beginTransaction();
        try {
            $event = Event::where('id', $data['event_id'])->first();

            $eventParticipant = EventParticipant::find($id);
            $eventParticipant->event_id = $data['event_id'];
            $eventParticipant->head_of_family_id = $data['head_of_family_id'];

            if (isset($data['quantity'])) {
                $eventParticipant->quantity = $data['quantity'];
            } else {
                $data['quantity'] = $eventParticipant->quantity;
            }

            $eventParticipant->total_price = $event->price * $data['quantity'];
            $eventParticipant->payment_status = $data['payment_status'];
            $eventParticipant->save();
            DB::commit();
            return $eventParticipant;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function delete(string $id)
    {
        DB::beginTransaction();
        try {
            $eventParticipant = EventParticipant::find($id);
            $eventParticipant->delete();
            DB::commit();
            return $eventParticipant;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
