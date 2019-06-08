<?php

namespace App\Http\Requests\v1\Group;

use App\Http\Requests\v1\MainRequest;
use App\Rules\UniqueTenant;
use App\Rules\ExistsTenant;

class CategoryRequest extends MainRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'             => [$this->requiredOrFilled(), 'string', 'max:50', new UniqueTenant('categories')],
            'description'       => 'nullable|string|min:255',
            'logo'              => 'nullable|image|mimes:jpeg,jpg,png,gif|max:1024',
            'is_active'         => 'nullable|boolean',
            
            /* relateion */
            'parent_id'         => ['nullable', 'integer', new ExistsTenant('categories')],

            'scoring_fields'    => ['nullable', 'array', new ExistsTenant('scoring_fields')],
            'scoring_fields.*'  => 'required|string|max:100',
            
            'keywords'          => 'nullable|array',
            'keywords.*'        => 'required|string|max:100',
        ];
    }
}
