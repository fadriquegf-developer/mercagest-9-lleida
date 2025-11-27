<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidIban implements Rule
{
    /**
     * The country rules.
     *
     * @var array
     */
    protected static $countryRules;

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
        return $this->checkIBAN($value);
    }

    /**
     * Validate IBAN.
     *
     * @param  string  $iban
     * @return bool
     */
    protected function checkIBAN($iban): bool
    {
        // remove white space or tabs
        $iban = preg_replace('/\s+/', '', $iban);

        // Check if IBAN contains white space or special characters
        if (preg_match('/\s|[\'^£$%&*()}{@#~?<>,|=_+¬-]/', $iban))
            return false;

        $countryRules = $this->getCountryRules();

        $countryCode = substr($iban, 0, 2);
        $countryObj = $countryRules['sepa'][$countryCode] ?? $countryRules['not_sepa'][$countryCode] ?? null;

        if ($countryObj === null)
            return false;

        // Get validation rules
        $rules = array_map(fn($attr) => $attr[1], $countryObj);

        // Validate IBAN against rules
        $tempIban = $iban;
        $ibanLength = 0;

        foreach ($rules as $rule) {
            $numbers = intval(preg_replace('/[^0-9]/', '', $rule));
            $letter = preg_replace('/[^a-zA-Z]/', '', $rule);
            $checkString = substr($tempIban, 0, $numbers);
            $ibanLength += $numbers;

            // Check if the string part is of the correct type
            if (($letter === 'a' && !ctype_alpha($checkString)) || ($letter === 'n' && !ctype_digit($checkString)))
                return false;

            $tempIban = substr($tempIban, $numbers);
        }

        return $ibanLength == strlen($iban);
    }

    /**
     * Get country rules. If not already loaded, load them.
     *
     * @return array
     */
    protected function getCountryRules(): array
    {
        if (self::$countryRules === null) {
            self::$countryRules = json_decode(
                file_get_contents(
                    resource_path('json/countries.json')
                ),
                true
            );
        }

        return self::$countryRules;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.iban');
    }
}
