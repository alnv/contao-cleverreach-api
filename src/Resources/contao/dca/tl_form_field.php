<?php

$GLOBALS['TL_DCA']['tl_form_field']['palettes']['radio'] = str_replace( 'options', 'options,getCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['radio'] );
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['select'] = str_replace( 'options', 'options,getCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['select'] );
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['checkbox'] = str_replace( 'options', 'options,getCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['checkbox'] );

$GLOBALS['TL_DCA']['tl_form_field']['fields']['getCleverreachNewsletter'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['getCleverreachNewsletter'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'tl_class'=>'clr'
    ],
    'sql' => "char(1) NOT NULL default ''"
];