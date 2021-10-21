<?php

namespace App\Http\Requests;

class PostAdminSaveRequest extends PostSaveRequest
{
    public function rules()
    {
        $rules = parent::rules();
        $rules['tags'] = ['sometimes', 'string'];
        $rules['image'] = 'sometimes|image';
        $rules['remove_image'] = 'sometimes|boolean';

        return $rules;
    }
}
