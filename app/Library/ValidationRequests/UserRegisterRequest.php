<?php namespace News\ValidationRequests;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use News\Abstracts\FormRequest;

class UserRegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:8',
        ];

    }

    public function prepareRequest(){
        $request = $this;
        return [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => $request['password'],
        ];
    }
}
