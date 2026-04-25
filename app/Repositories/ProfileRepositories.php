<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoriesInterface;
use App\Models\Profile;
use Exception;
use Illuminate\Support\Facades\DB;

class ProfileRepositories implements ProfileRepositoriesInterface
{
    public function getProfile()
    {
        return Profile::first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = new Profile();
            $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agriculutral_area = $data['agriculutral_area'];
            $profile->total_area = $data['total_area'];

            $profile->save();

            if (array_key_exists('images', $data)) {
                foreach ($data['images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public')
                    ]);
                }
            }

            DB::commit();

            return Profile::with('profileImages')->find($profile->id);
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function update(
        array $data
    ) {
        DB::beginTransaction();

        try {
            $profile = Profile::first();

            if (isset($data['thumbnail'])) {
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agriculutral_area = $data['agriculutral_area'];
            $profile->total_area = $data['total_area'];

            if (array_key_exists('images', $data)) {
                foreach ($data['images'] as $image) {
                    $profile->profileImages()->create([
                        'image' => $image->store('assets/profiles', 'public')
                    ]);
                }
            }

            $profile->save();
            DB::commit();

            return $profile->load('profileImages');
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
