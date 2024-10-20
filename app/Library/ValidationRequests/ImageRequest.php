<?php namespace News\ValidationRequests;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use News\Abstracts\FormRequest;
use News\Models\Image;

class ImageRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if($this->method() == 'PUT'){
            return [
            ];
        }
        return [
            'name' => 'required',
            'image' => 'required',
        ];

    }

    public function prepareRequest($userId){
        $request = $this;
        if($request['image']){
            $image = $this->uploadImage($request['image']);
        }else{
            $image = [
                'url' => null,
                'extension' => null,
            ];
        }
        return [
            'userId' => $userId,
            'name' => $request['name'],
            'image' => $image['url'],
            'extension' =>$image['extension'],
            'status' => Image::HIDDEN,
        ];
    }

    private function uploadImage($file){
        $image_64 = $file;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10).'.'.$extension;
        Storage::disk('local')->put("public/profile/$imageName", base64_decode($image) , 'public');
        return [
            'url' => public_path('storage/profile/'.$imageName),
            'extension' => $extension,
        ];
    }
}
