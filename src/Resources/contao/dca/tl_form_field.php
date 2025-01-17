<?php

use Alnv\ContaoCleverreachApi\DataContainer\Form;

$GLOBALS['TL_DCA']['tl_form_field']['config']['onload_callback'][] = [Form::class, 'setCleverreachGroups'];
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['radio'] = str_replace('options', 'options,setCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['radio']);
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['select'] = str_replace('options', 'options,setCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['select']);
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['checkbox'] = str_replace('options', 'options,setCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['checkbox']);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['setCleverreachNewsletter'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'submitOnChange' => true,
        'tl_class' => 'clr'
    ],
    'sql' => "char(1) NOT NULL default ''"
];