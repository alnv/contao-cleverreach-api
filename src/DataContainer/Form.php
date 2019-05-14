<?php

namespace Alnv\ContaoCleverreachApi\DataContainer;

use Alnv\ContaoCleverreachApi\API\Cleverreach;


class Form {


    public function getForms() {

        $arrReturn = [];
        $objCleverreachApi = new Cleverreach();
        $arrGroups = $objCleverreachApi->getForms();

        foreach ( $arrGroups as $arrGroup ) {

            $arrReturn[ $arrGroup['value'] ] = $arrGroup['label'];
        }

        return $arrReturn;
    }
}