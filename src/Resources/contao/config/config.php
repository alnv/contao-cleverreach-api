<?php

// $GLOBALS['TL_HOOKS']['compileFormFields'][] = [ 'Alnv\ContaoCleverreachApi\Hooks\Form', 'compileFormFields' ];
$GLOBALS['TL_HOOKS']['processFormData'][] = [ 'Alnv\ContaoCleverreachApi\Hooks\Form', 'processFormData' ];