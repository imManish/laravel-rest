<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class PasswordValidationRule implements Rule
{
    protected $message = 'Password Should Be Greather then 6 to 12 character long alpha numbric with at least 1 Special character.';
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
        if (!empty($value) && strlen($value) <= 12 && preg_match('/[A-Za-z\d$!^(){}?\[\]<>~%@#&*+=_-]{8,40}$/',$value))  {
            return true;
        }
        return false;
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
