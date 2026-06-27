<?php

declare(strict_types=1);

namespace MetaMyKad\Controllers;

require_once src_path('Controllers/RegistrationController/part-store.php');
require_once src_path('Controllers/RegistrationController/part-store-persist.php');
require_once src_path('Controllers/RegistrationController/part-delete-file.php');

final class RegistrationController extends BaseController
{
    use RegistrationStoreTrait;
    use RegistrationStorePersistTrait;
    use RegistrationDeleteFileTrait;
}
