<?php namespace News\ValidationRequests;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use News\Abstracts\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'age' => 'required',
        ];

    }

    public function prepareRequest(){
        $request = $this;
        return [
            'name' => $request['name'],
            'age' => $request['age'],
            'profileImage' =>$request['profileImage'] ? $this->uploadImage($request['profileImage']) : null,
        ];
    }

    public function uploadImage($file){
        $image_64 = $file;
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(10).'.'.$extension;
        Storage::disk('local')->put("public/profile/$imageName", base64_decode($image) , 'public');
        return public_path('storage/profile/'.$imageName);
    }
}
