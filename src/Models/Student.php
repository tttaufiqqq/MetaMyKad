<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

use InvalidArgumentException;

final class Student extends BaseModel
{
    protected string $table = 'students';

    public function deriveFromIc(string $icNumber): array
    {
        $digits = preg_replace('/\D+/', '', $icNumber) ?? '';
        if (strlen($digits) !== 12) {
            throw new InvalidArgumentException('IC number must contain exactly 12 digits.');
        }

        $birthPart = substr($digits, 0, 6);
        $stateCode = substr($digits, 6, 2);
        $lastDigit = (int) substr($digits, -1);
        $yearPrefix = ((int) substr($birthPart, 0, 2)) > (int) date('y') ? '19' : '20';
        $dateOfBirth = \DateTimeImmutable::createFromFormat('Ymd', $yearPrefix . $birthPart);

        if ($dateOfBirth === false) {
            throw new InvalidArgumentException('IC date segment is not valid.');
        }

        return [
            'date_of_birth' => $dateOfBirth->format('Y-m-d'),
            'gender' => $lastDigit % 2 === 0 ? 'F' : 'M',
            'state_of_birth' => $this->resolveState($stateCode),
            'age' => (new \DateTimeImmutable('today'))->diff($dateOfBirth)->y,
        ];
    }

    public function classifyEmail(string $email): string
    {
        $domain = strtolower((string) substr(strrchr($email, '@') ?: '', 1));
        if ($domain === '') {
            return 'personal';
        }

        if (str_contains($domain, 'student') || str_ends_with($domain, '.edu.my')) {
            return 'student';
        }

        if (in_array($domain, ['gmail.com', 'yahoo.com', 'outlook.com', 'hotmail.com'], true)) {
            return 'personal';
        }

        return 'work';
    }

    private function resolveState(string $stateCode): string
    {
        $map = [
            '01' => 'Johor',
            '02' => 'Kedah',
            '03' => 'Kelantan',
            '04' => 'Melaka',
            '05' => 'Negeri Sembilan',
            '06' => 'Pahang',
            '07' => 'Pulau Pinang',
            '08' => 'Perak',
            '09' => 'Perlis',
            '10' => 'Selangor',
            '11' => 'Terengganu',
            '12' => 'Sabah',
            '13' => 'Sarawak',
            '14' => 'Wilayah Persekutuan Kuala Lumpur',
            '15' => 'Wilayah Persekutuan Labuan',
            '16' => 'Wilayah Persekutuan Putrajaya',
        ];

        return $map[$stateCode] ?? 'Unknown';
    }
}
