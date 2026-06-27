<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

use InvalidArgumentException;

trait StudentIcEmailTrait
{
    public function deriveFromIc(string $icNumber): array
    {
        $digits = preg_replace('/\D+/', '', $icNumber) ?? '';
        if (strlen($digits) !== 12) {
            throw new InvalidArgumentException('IC number must contain exactly 12 digits.');
        }

        $birthPart   = substr($digits, 0, 6);
        $stateCode   = substr($digits, 6, 2);
        $lastDigit   = (int) substr($digits, -1);
        $yearPrefix  = ((int) substr($birthPart, 0, 2)) > (int) date('y') ? '19' : '20';
        $dateOfBirth = \DateTimeImmutable::createFromFormat('Ymd', $yearPrefix . $birthPart);

        if ($dateOfBirth === false) {
            throw new InvalidArgumentException('IC date segment is not valid.');
        }

        return [
            'date_of_birth'  => $dateOfBirth->format('Y-m-d'),
            'gender'         => $lastDigit % 2 === 0 ? 'F' : 'M',
            'state_of_birth' => $this->resolveState($stateCode),
            'age'            => (new \DateTimeImmutable('today'))->diff($dateOfBirth)->y,
        ];
    }

    public function classifyEmail(string $email): string
    {
        $domain = strtolower((string) substr(strrchr($email, '@') ?: '', 1));
        if ($domain === '') {
            return 'Personal';
        }

        if (str_contains($domain, 'student') || str_ends_with($domain, '.edu.my')) {
            return 'Student';
        }

        if (in_array($domain, ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'], true)) {
            return 'Personal';
        }

        return 'Work';
    }

    private function resolveState(string $stateCode): string
    {
        $map = [
            '01' => 'Johor',                            '02' => 'Kedah',
            '03' => 'Kelantan',                         '04' => 'Melaka',
            '05' => 'Negeri Sembilan',                  '06' => 'Pahang',
            '07' => 'Pulau Pinang',                     '08' => 'Perak',
            '09' => 'Perlis',                           '10' => 'Selangor',
            '11' => 'Terengganu',                       '12' => 'Sabah',
            '13' => 'Sarawak',
            '14' => 'Wilayah Persekutuan Kuala Lumpur',
            '15' => 'Wilayah Persekutuan Labuan',
            '16' => 'Wilayah Persekutuan Putrajaya',
            '21' => 'Johor',        '22' => 'Johor',        '23' => 'Johor',        '24' => 'Johor',
            '25' => 'Kedah',        '26' => 'Kedah',        '27' => 'Kedah',
            '28' => 'Kelantan',     '29' => 'Kelantan',
            '30' => 'Melaka',
            '31' => 'Negeri Sembilan',                  '59' => 'Negeri Sembilan',
            '32' => 'Pahang',       '33' => 'Pahang',
            '34' => 'Pulau Pinang', '35' => 'Pulau Pinang',
            '36' => 'Perak',        '37' => 'Perak',        '38' => 'Perak',        '39' => 'Perak',
            '40' => 'Perlis',
            '41' => 'Selangor',     '42' => 'Selangor',     '43' => 'Selangor',     '44' => 'Selangor',
            '45' => 'Terengganu',   '46' => 'Terengganu',
            '47' => 'Sabah',        '48' => 'Sabah',        '49' => 'Sabah',
            '50' => 'Sarawak',      '51' => 'Sarawak',      '52' => 'Sarawak',      '53' => 'Sarawak',
            '54' => 'Wilayah Persekutuan Kuala Lumpur',
            '55' => 'Wilayah Persekutuan Kuala Lumpur',
            '56' => 'Wilayah Persekutuan Kuala Lumpur',
            '57' => 'Wilayah Persekutuan Kuala Lumpur',
            '58' => 'Wilayah Persekutuan Labuan',
        ];

        return $map[$stateCode] ?? 'Unknown';
    }
}
