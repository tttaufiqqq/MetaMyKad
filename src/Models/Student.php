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

    public function findByFullName(string $fullName): array|false
    {
        $stmt = \MetaMyKad\Core\Database::connection()->prepare(
            'SELECT * FROM students WHERE full_name = :n LIMIT 1'
        );
        $stmt->execute(['n' => $fullName]);
        return $stmt->fetch();
    }

    public function findInCentral(string $matric): array|false
    {
        $stmt = \MetaMyKad\Core\Database::connection()->prepare(
            'SELECT id, matric_no, full_name, phone_no, password
               FROM mmdb2026.vstu WHERE LOWER(matric_no) = LOWER(:m) LIMIT 1'
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

    public function getAllFromCentral(): array
    {
        $pdo = \MetaMyKad\Core\Database::connection();
        return $pdo->query(
            'SELECT c.matric_no, c.full_name, c.group_no,
                    m.id   AS metamykad_id,
                    m.badge,
                    fm.id  AS photo_id
               FROM mmdb2026.vstu c
               LEFT JOIN students m ON m.matric_number = c.matric_no
               LEFT JOIN file_metadata fm
                 ON fm.student_id = m.id
                AND fm.file_type = \'photo\'
                AND fm.id = (
                    SELECT MAX(id) FROM file_metadata
                    WHERE student_id = m.id AND file_type = \'photo\'
                )
              ORDER BY c.full_name'
        )->fetchAll();
    }

    /**
     * Insert a minimal stub row for a central student logging in for the first time.
     * Only matric, password, full_name, and phone are available from mmdb2026.vstu.
     * All other fields (ic_number, email, dob, gender, state, age) remain NULL.
     * The stub is detected later by ic_number IS NULL and completed via /register.
     */
    public function createStub(array $central): int
    {
        $db = \MetaMyKad\Core\Database::connection();
        $db->prepare(
            'INSERT INTO students
                 (matric_number, password, full_name, phone, badge, created_at, updated_at)
             VALUES (:matric, :pw, :name, :phone, \'Pendaftar\', NOW(), NOW())'
        )->execute([
            'matric' => $central['matric_no'],
            'pw'     => $central['password'],
            'name'   => $central['full_name'],
            'phone'  => $central['phone_no'] ?? '',
        ]);
        return (int) $db->lastInsertId();
    }

    /**
     * Fill in the NULL fields of a stub row when the student completes their profile.
     * Called instead of sp_register_student because the SP would attempt INSERT and
     * hit a DUPLICATE KEY on matric_number (the stub row already exists).
     */
    public function completeStub(int $id, array $data): void
    {
        \MetaMyKad\Core\Database::connection()->prepare(
            'UPDATE students
             SET ic_number=:ic, full_name=:name, phone=:phone, email=:email,
                 email_category=:cat, date_of_birth=:dob, gender=:gender,
                 state_of_birth=:state, age=:age, updated_at=NOW()
             WHERE id=:id'
        )->execute([
            'ic'    => $data['ic_number'],
            'name'  => $data['full_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'cat'   => $data['email_category'],
            'dob'   => $data['date_of_birth'],
            'gender'=> $data['gender'],
            'state' => $data['state_of_birth'],
            'age'   => $data['age'],
            'id'    => $id,
        ]);
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
