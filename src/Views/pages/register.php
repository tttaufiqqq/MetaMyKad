<?php
$_vstuMatric          = $vstuMatric ?? '';
$_isProfileCompletion = $_vstuMatric !== '';

if ($vstuGate ?? false) {
    require src_path('Views/pages/register/part-vstu-gate.php');
    return;
}

require src_path('Views/pages/register/part-prefill-modal.php');
require src_path('Views/pages/register/part-form-personal.php');
require src_path('Views/pages/register/part-form-upload.php');
require src_path('Views/pages/register/part-script.php');
