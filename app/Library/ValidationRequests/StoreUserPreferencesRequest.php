<?php namespace News\ValidationRequests;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use News\Abstracts\FormRequest;

class StoreUserPreferencesRequest extends FormRequest
{
    public function authorize()
    {
        return true; 
    }

    public function rules()
    {
        return [
            'preferred_source' => 'string|nullable',
            'preferred_category' => 'string|nullable',
            'preferred_author' => 'string|nullable',
        ];
    }

    public function prepareRequest(){
        $request = $this;
        return [
            'preferred_source' => $request['preferred_source'],
            'preferred_category' => $request['preferred_category'],
            'preferred_author' =>$request['preferred_author'],
        ];
    }
}
