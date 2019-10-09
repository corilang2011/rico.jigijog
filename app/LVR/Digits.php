<?php
namespace App\LVR;

use Illuminate\Contracts\Validation\Rule;

class Digits extends Phone
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed  $value
     *s
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->isDigits($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return ':attribute must be in 11 digits only phone format';
    }
}
