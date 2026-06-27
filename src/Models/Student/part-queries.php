<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

use MetaMyKad\Core\Database;

trait StudentQueriesTrait
{
    public function findByIc(string $icNumber): array|false
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM students WHERE ic_number = :ic LIMIT 1'
        );
        $stmt->execute(['ic' => $icNumber]);
        return $stmt->fetch();
    }

    public function findByMatric(string $matric): array|false
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM students WHERE matric_number = :m LIMIT 1'
        );
        $stmt->execute(['m' => $matric]);
        return $stmt->fetch();
    }

    public function findByFullName(string $fullName): array|false
    {
        $stmt = Database::connection()->prepare(
            'SELECT * FROM students WHERE full_name = :n LIMIT 1'
        );
        $stmt->execute(['n' => $fullName]);
        return $stmt->fetch();
    }

    public function findInCentral(string $matric): array|false
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, matric_no, full_name, phone_no, password
               FROM mmdb2026.vstu WHERE LOWER(matric_no) = LOWER(:m) LIMIT 1'
        );
        $stmt->execute(['m' => $matric]);
        return $stmt->fetch();
    }

    public function updateProfile(int $id, array $data): void
    {
        Database::connection()->prepare(
            'UPDATE students SET full_name = :full_name, phone = :phone,
             email = :email, email_category = :email_category
             WHERE id = :id'
        )->execute([
            'full_name'      => $data['full_name'],
            'phone'          => $data['phone'],
            'email'          => $data['email'],
            'email_category' => $data['email_category'],
            'id'             => $id,
        ]);
    }

    public function updateIcAndDerived(int $id, string $icHash, array $derived): void
    {
        Database::connection()->prepare(
            'UPDATE students
             SET ic_number = :ic, date_of_birth = :dob, gender = :gender, state_of_birth = :state
             WHERE id = :id AND ic_number IS NULL'
        )->execute([
            'ic'     => $icHash,
            'dob'    => $derived['date_of_birth'],
            'gender' => $derived['gender'],
            'state'  => $derived['state_of_birth'],
            'id'     => $id,
        ]);
    }

    public function updatePassword(int $id, string $newHash): void
    {
        Database::connection()->prepare(
            'UPDATE students SET password = :pw WHERE id = :id'
        )->execute(['pw' => $newHash, 'id' => $id]);
    }

    public function getAllWithPhoto(): array
    {
        return Database::connection()->query(
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
        return Database::connection()->query(
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
}
