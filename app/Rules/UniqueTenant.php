<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class UniqueTenant implements Rule
{
    private $table;

    private $field;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($table, $field = null)
    {
        $this->table = $table;
        $this->field = $field;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $result = DB::table( $this->table )
            ->where($this->field ?? $attribute, $value)
            ->where('tenant_id', $this->getTenant() )
            ->count();

        return $result === 0;
    }

    public function getTenant()
    {
        return cache()->rememberForever(request()->getHost(), function () {
            return Hostname::whereDomain( request()->getHost() )->first()->tenant_id ?? abort(404);
        });
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.unique');
    }
}