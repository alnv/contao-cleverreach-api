<?php

namespace Alnv\ContaoCleverreachApi\Hooks;

use Alnv\ContaoCleverreachApi\API\Cleverreach;


class Form {


    public function processFormData( $arrPost, $arrForm, $arrFiles ) {

        if ( $arrForm['useCleverreachApi'] ) {

            $arrNewsletter = $arrPost['newsletter'];

            if ( !is_array( $arrNewsletter ) ) {

                $arrNewsletter = [ $arrPost['newsletter'] ];
            }

            $objCleverreachApi = new Cleverreach();
            $objCleverreachApi->subscribe(
                $arrNewsletter,
                [
                    'activated' => 0,
                    'registered' => time(),
                    'email' => $arrPost['email'],
                    'source' => \Environment::get('url'),
                    'global_attributes' => [ 'firstname' => $arrPost['firstname'], 'lastname' => $arrPost['lastname'], 'gender' => $arrPost['gender'] ]
                ],
                $arrForm['cleverreachActiveFormId']
            );
        }
    }


    public function compileFormFields( $arrFields, $intFormId, $objForm ) {

        foreach ( $arrFields as $objField ) {

            if ( $objField->getCleverreachNewsletter ) {

                $objCleverreachApi = new Cleverreach();
                $objField->options = serialize( $objCleverreachApi->getGroups() );
            }
        }

        return $arrFields;
    }
}