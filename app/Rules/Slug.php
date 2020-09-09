<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Slug implements Rule
{
    /**
     * @var string
     */
    private $message;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        if(preg_match('/_/',$value)) {
            $this->message = trans('validation.no_underscores');
            return false;
        }
        if(preg_match('/^-/',$value)) {
            $this->message = trans('validation.no_start_with_dash');
            return false;
        }
        if(preg_match('/-$/',$value)) {
            $this->message = trans('validation.no_end_with_dash');
            return false;
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
