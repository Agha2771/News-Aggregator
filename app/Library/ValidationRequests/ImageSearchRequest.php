<?php namespace News\ValidationRequests;


use News\Abstracts\FormRequest;

class ImageSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [

        ];

    }

    public function prepareRequest($userId){
        $request = $this;
        return [
            'userId' => $userId,
            'date' => $request['date'],
            'name' => $request['name'],
            'extension' => $request['extension'],
            'status' => $request['status'],
        ];
    }
}
