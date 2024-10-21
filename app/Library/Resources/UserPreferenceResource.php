<?php namespace News\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPreferenceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id, // Assuming there's an ID field
            'user_id' => $this->user_id, // Assuming there's a user_id field
            'preferred_sources' => $this->preferred_sources, // Adjust based on your actual field
            'preferred_categories' => $this->preferred_categories, // Adjust based on your actual field
            'preferred_authors' => $this->preferred_authors, // Adjust based on your actual field
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
