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

    public function findByIc(string $icNumber): array|false
    {
        $stmt = \MetaMyKad\Core\Database::connection()->prepare(
            'SELECT * FROM students WHERE ic_number = :ic LIMIT 1'
        );
        $stmt->execute(['ic' => $icNumber]);
        return $stmt->fetch();
    }

    public function findByMatric(string $matric): array|false
    {
        $stmt = \MetaMyKad\Core\Database::connection()->prepare(
            'SELECT * FROM students WHERE matric_number = :m LIMIT 1'
        );
        $stmt->execute(['m' => $matric]);
        return $stmt->fetch();
    }

    public function updateProfile(int $id, array $data): void
    {
        $db = \MetaMyKad\Core\Database::connection();
        $stmt = $db->prepare(
            'UPDATE students SET full_name = :full_name, phone = :phone,
             email = :email, email_category = :email_category
             WHERE id = :id'
        );
        $stmt->execute([
            'full_name'      => $data['full_name'],
            'phone'          => $data['phone'],
            'email'          => $data['email'],
            'email_category' => $data['email_category'],
            'id'             => $id,
        ]);
    }

    public function updatePassword(int $id, string $newHash): void
    {
        $stmt = \MetaMyKad\Core\Database::connection()->prepare(
            'UPDATE students SET password = :pw WHERE id = :id'
        );
        $stmt->execute(['pw' => $newHash, 'id' => $id]);
    }

    public function getAllWithPhoto(): array
    {
        $pdo = \MetaMyKad\Core\Database::connection();
        return $pdo->query(
            'SELECT s.id, s.full_name, s.badge,
                    fm.id AS photo_id
             FROM students s
             LEFT JOIN file_metadata fm
               ON fm.student_id = s.id
              AND fm.file_type = \'photo\'
              AND fm.id = (
                  SELECT MAX(id) FROM file_metadata
                  WHERE student_id = s.id AND file_type = \'photo\'
              )
             ORDER BY s.full_name'
        )->fetchAll();
    }

    private function resolveState(string $stateCode): string
    {
        $map = [
            // Primary codes
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
            // Secondary codes
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
