<?php namespace News\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
class ArticleResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
            'category' => $this->category,
            'source' => $this->source,
            'published_at' => $this->published_at, 
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
