<?php namespace News\Repositories\Image;


use Carbon\Carbon;
use News\Abstracts\EloquentRepository;
use News\Models\Image;

class ImageEloquentRepository extends EloquentRepository implements ImageRepositoryInterface
{
    public function __construct()
    {
        $this->model = new Image();
    }

    public function create($data){
        $image = new $this->model();
        return $this->prepareData($image,$data);
    }

    private function prepareData($image,$data){
        if (isset($data['name'])){
            $image->name = $data['name'];
        }

        if(isset($data['userId'])){
            $image->user_id = $data['userId'];
        }

        if(isset($data['image']) and $data['image']){
            $image->url = $data['image'];
        }

        if(isset($data['extension']) and $data['extension']){
            $image->extension = $data['extension'];
        }

        if(isset($data['status'])){
            $image->status = $data['status'];
        }

        $image->save();
        return $image;
    }

    public function update($data,$id){
        $image = $this->model->where('id',$id)->first();
        return $this->prepareData($image,$data);
    }

    public function search($data){
        $images = $this->model->where('user_id',$data['userId']);
        if(isset($data['name']) and $data['name']){
            $images->where('name',$data['name']);
        }

        if(isset($data['extension']) and $data['extension']){
            $images->where('extension',$data['extension']);
        }

        if(isset($data['date']) and $data['date']){
            $images->whereDate('created_at',Carbon::parse($data['date']));
        }

        if(isset($data['status']) and $data['status']){
            $images->where('status',(int)$data['status']);
        }

        return $images->get();
    }
}
