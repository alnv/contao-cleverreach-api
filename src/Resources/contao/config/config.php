<?php

use Alnv\ContaoCleverreachApi\Hooks\Form;

$GLOBALS['TL_HOOKS']['processFormData'][] = [Form::class, 'processFormData'];