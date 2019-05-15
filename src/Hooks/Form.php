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

            if ( empty( $arrNewsletter ) || ( isset( $arrNewsletter[0] ) && $arrNewsletter[0] == '' ) ) {

                return null;
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
}