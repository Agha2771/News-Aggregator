<?php namespace News\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name' => $this->name,
            'email' => $this->email,
            'age' => $this->age,
            'profileImage' => $this->profile_picture,
            'isVerified' => $this->is_verified,
        ];
    }
}
