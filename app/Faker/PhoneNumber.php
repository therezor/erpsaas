<?php

namespace App\Faker;

use App\Models\Locale\Country;
use Faker\Provider\PhoneNumber as BasePhoneNumber;

class PhoneNumber extends BasePhoneNumber
{
    public function phoneNumberForCountryCode(string $countryCode): string
    {
        $phoneCode = Country::where('iso_code_2', $countryCode)->first()->phone_code;

        $filteredFormats = array_filter(
            static::$e164Formats,
            static fn ($format) => str_starts_with($format, "+{$phoneCode}")
        );

        if (empty($filteredFormats)) {
            return $this->e164PhoneNumber();
        }

        return self::numerify($this->generator->parse($this->generator->randomElement($filteredFormats)));
    }
}
