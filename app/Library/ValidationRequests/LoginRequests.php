<?php namespace News\ValidationRequests;


use News\Abstracts\FormRequest;

class LoginRequests extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];

    }

    public function prepareRequest(){
        $request = $this;
        return [
            'email' => $request['email'],
            'password' => $request['password'],
        ];
    }
}
