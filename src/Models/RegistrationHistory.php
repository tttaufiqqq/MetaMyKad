<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

final class RegistrationHistory extends BaseModel
{
    protected string $table = 'registration_history';

    public function writeForIc(string $icNumber, string $action): array
    {
        return $this->callProcedure('sp_write_registration_history', [$icNumber, $action]);
    }

    public function getByIc(string $icNumber): array
    {
        return $this->callProcedure('sp_get_student_history', [$icNumber]);
    }
}
