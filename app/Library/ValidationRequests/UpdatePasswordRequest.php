<?php namespace News\ValidationRequests;


use News\Abstracts\FormRequest;

class UpdatePasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'oldPassword' => 'required',
            'password' => 'required|min:8|confirmed',
        ];

    }

    public function prepareRequest(){
        $request = $this;
        return [
            'oldPassword' => $request['oldPassword'],
            'password' => $request['password'],
        ];
    }
}
