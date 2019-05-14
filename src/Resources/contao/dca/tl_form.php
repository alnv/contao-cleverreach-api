<?php

$GLOBALS['TL_DCA']['tl_form']['palettes']['__selector__'][] = 'useCleverreachApi';
$GLOBALS['TL_DCA']['tl_form']['palettes']['default'] = str_replace( 'sendViaEmail;', 'sendViaEmail;{cleverreach_legend},useCleverreachApi;', $GLOBALS['TL_DCA']['tl_form']['palettes']['default'] );
$GLOBALS['TL_DCA']['tl_form']['subpalettes']['useCleverreachApi'] = 'cleverreachActiveFormId';

$GLOBALS['TL_DCA']['tl_form']['fields']['useCleverreachApi'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_form']['useCleverreachApi'],
    'inputType' => 'checkbox',
    'eval' => [
        'submitOnChange' => true,
        'tl_class' => 'clr'
    ],
    'exclude' => true,
    'sql' => "char(1) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_form']['fields']['cleverreachActiveFormId'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_form']['cleverreachActiveFormId'],
    'inputType' => 'select',
    'options_callback' => [ 'Alnv\ContaoCleverreachApi\DataContainer\Form', 'getForms' ],
    'eval' => [
        'includeBlankOption' => true,
        'tl_class' => 'w50',
        'chosen' => true
    ],
    'exclude' => true,
    'sql' => "varchar(64) NOT NULL default ''"
];