<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

require_once src_path('Controllers/StudentController/part-read.php');
require_once src_path('Controllers/StudentController/part-write.php');

final class StudentController extends BaseController
{
    use StudentControllerReadTrait;
    use StudentControllerWriteTrait;
}
