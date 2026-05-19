<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

final class StudentProfileSummaryView extends BaseModel
{
    protected string $table = 'vw_student_profile_summary';
    protected string $primaryKey = 'student_id';

    public function searchByAttribute(
        ?string $gender = null,
        ?string $state = null,
        ?string $emailCategory = null,
        ?string $badge = null,
        ?string $fileType = null
    ): array {
        return $this->callProcedure('sp_search_attribute_students', [
            $gender,
            $state,
            $emailCategory,
            $badge,
            $fileType,
        ]);
    }

    public function refreshStudentBadge(int $studentId): array
    {
        return $this->callProcedure('sp_refresh_student_badge', [$studentId]);
    }
}
