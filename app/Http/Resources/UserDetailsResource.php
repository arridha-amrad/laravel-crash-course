<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource(User::find($this->user_id)),
            'phoneNumber' => $this->phone_number,
            'city' => $this->city,
            'postalCode' => $this->postal_code
        ];
    }
}
