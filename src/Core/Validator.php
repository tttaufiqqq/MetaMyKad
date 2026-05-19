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
            }
        }

        return $errors;
    }
}
