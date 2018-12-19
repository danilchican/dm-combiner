<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        dd(json_encode(request()->input('configuration')));
        return [
            'title'         => 'required|string|min:3|max:100',
            'normalize'     => 'required|in:true,false',
            'scale'         => 'required|in:true,false',
            'columns'       => 'required|array',
            'configuration' => 'required|array',
        ];
    }
}
