<?php

$GLOBALS['TL_DCA']['tl_form_field']['config']['onload_callback'][] = [ 'Alnv\ContaoCleverreachApi\DataContainer\Form', 'setCleverreachGroups' ];
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['radio'] = str_replace( 'options', 'options,setCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['radio'] );
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['select'] = str_replace( 'options', 'options,setCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['select'] );
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['checkbox'] = str_replace( 'options', 'options,setCleverreachNewsletter', $GLOBALS['TL_DCA']['tl_form_field']['palettes']['checkbox'] );

$GLOBALS['TL_DCA']['tl_form_field']['fields']['setCleverreachNewsletter'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['setCleverreachNewsletter'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => [
        'submitOnChange' => true,
        'tl_class'=>'clr'
    ],
    'sql' => "char(1) NOT NULL default ''"
];