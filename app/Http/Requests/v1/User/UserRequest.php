<?php

namespace App\Http\Requests\User\v1;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

class UserRequest extends FormRequest
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
        $rules = [
            'city_id'           => 'nullable|integer|exists:cities,id',
            'first_name'        => 'nullable|string|max:20',
            'first_name'        => 'nullable|string|max:30',
            'phones'            => 'nullable|array',
            'phones.*'          => ['required', 'string', 'regex:/09(1[0-9]|3[1-9]|2[1-9])-?[0-9]{3}-?[0-9]{4}/'],
            'social_links'      => 'nullable|array',
            'social_links.*'    => 'required|string|max:100',
            'email'             => 'required|unique:users,email',
            'avatar'            => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'address'           => 'nullable|string|max:255',
            'postal_code'       => 'nullable|digits:10',
            
            // relations
            'permissions'       => 'nullable|array',
            'permissions.*'     => 'required|integer|exists:permissions,id',
            
            'roles'             => 'nullable|array',
            'roles.*'           => 'required|integer|exists:roles,id',
        ];

        if ( $this->method() === 'PUT' && $this->route('user')->email === $this->email )
            $rules['email'] = 'required';

        return $rules;
    }
}
