<?php namespace News\Resources;


use Illuminate\Http\Resources\Json\JsonResource;

class ImageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'userId' =>$this->user_id,
            'name' => $this->name,
            'url' => $this->url,
            'extension' => $this->extension,
            'status' => $this->status,
        ];
    }
}
