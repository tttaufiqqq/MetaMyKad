<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

use MetaMyKad\Core\Database;

trait StudentStubTrait
{
    /**
     * Insert a minimal stub row for a central student logging in for the first time.
     * Only matric, password, full_name, and phone are available from mmdb2026.vstu.
     * All other fields (ic_number, email, dob, gender, state, age) remain NULL.
     * The stub is detected later by ic_number IS NULL and completed via /register.
     */
    public function createStub(array $central): int
    {
        $db = Database::connection();
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
        Database::connection()->prepare(
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
}
