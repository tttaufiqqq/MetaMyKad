<?php

declare(strict_types=1);

namespace MetaMyKad\Models;

require_once src_path('Models/Student/part-ic-email.php');
require_once src_path('Models/Student/part-queries.php');
require_once src_path('Models/Student/part-stub.php');

final class Student extends BaseModel
{
    protected string $table = 'students';

    use StudentIcEmailTrait;
    use StudentQueriesTrait;
    use StudentStubTrait;
}
