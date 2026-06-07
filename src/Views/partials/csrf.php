<?php

use MetaMyKad\Core\CSRF;
?>
<input type="hidden" name="_csrf" value="<?= e(CSRF::token()) ?>">
