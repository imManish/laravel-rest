<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class UsernameValidationRule implements Rule
{
    protected $message = 'Username should be alphabatic!';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!empty($value) && ctype_alpha(str_replace(' ', '', $value)) === false) {
            return false;
        } else {
            return true;
        }
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
