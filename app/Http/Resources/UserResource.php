<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'head_of_family' => new HeadOfFamilyResource($this->whenLoaded('headOfFamily')),
            'family_member' => new FamilyMemberResource($this->whenLoaded('familyMembers')),
        ];
    }
}
