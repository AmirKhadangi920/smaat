<?php

namespace App\Http\Requests\v1\Feature;

use Illuminate\Foundation\Http\FormRequest;

class WarrantyRequest extends FormRequest
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
        return [
            'title'             => 'required|string|max:50',
            'description'       => 'nullable|string|max:255',
            'expire'            => 'required|string|max:20',
            'logo'              => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',

            /* relateion */
            'categories'        => 'nullable|array',
            'categories.*'      => 'required|integer|exists:categories,id'
        ];
    }
}
