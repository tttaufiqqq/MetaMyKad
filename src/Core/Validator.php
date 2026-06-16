<?php

declare(strict_types=1);

namespace MetaMyKad\Core;

final class Validator
{
    public static function validate(array $input, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = trim((string) ($input[$field] ?? ''));

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && $value === '') {
                    $errors[$field][] = 'This field is required.';
                }

                if ($rule === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = 'Please enter a valid email address.';
                }

                if ($rule === 'ic' && ($value === '' || !preg_match('/^\d{12}$/', $value))) {
                    $errors[$field][] = 'IC number must contain exactly 12 digits.';
                }

                if ($rule === 'phone' && $value !== ''
                    && !preg_match('/^(\+?60|0)[0-9\-\s]{7,11}$/', $value)) {
                    $errors[$field][] = 'Please enter a valid Malaysian phone number (e.g., 012-3456789).';
                }

                if ($rule === 'passport' && $value !== ''
                    && !preg_match('/^[A-Z0-9]{6,20}$/i', $value)) {
                    $errors[$field][] = 'Passport number must be 6–20 alphanumeric characters.';
                }
            }
        }

        return $errors;
    }
}
