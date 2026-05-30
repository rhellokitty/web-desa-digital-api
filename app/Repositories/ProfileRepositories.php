<?php

namespace App\Repositories;

use App\Interfaces\ProfileRepositoriesInterface;
use App\Models\Profile;
use App\Models\ProfileImage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileRepositories implements ProfileRepositoriesInterface
{
    private function deleteFile(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function getProfile()
    {
        return Profile::with('profileImages')->first();
    }

    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $profile = new Profile();
            $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->address = $data['address'];
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
                $this->deleteFile($profile->thumbnail);
                $profile->thumbnail = $data['thumbnail']->store('assets/profiles', 'public');
            }

            $profile->name = $data['name'];
            $profile->about = $data['about'];
            $profile->address = $data['address'];
            $profile->headman = $data['headman'];
            $profile->people = $data['people'];
            $profile->agriculutral_area = $data['agriculutral_area'];
            $profile->total_area = $data['total_area'];

            if (!empty($data['deleted_images'])) {
                $imagesToDelete = ProfileImage::where('profile_id', $profile->id)
                    ->whereIn('id', $data['deleted_images'])
                    ->get();

                foreach ($imagesToDelete as $image) {
                    $this->deleteFile($image->image);
                    $image->delete();
                }
            }

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

    public function delete(
        string $id
    ) {
        DB::beginTransaction();

        try {
            $profile = Profile::find($id);
            $profile->delete();

            DB::commit();
            return $profile;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
