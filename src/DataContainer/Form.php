<?php

namespace Alnv\ContaoCleverreachApi\DataContainer;

use Contao\Input;
use Contao\Database;
use Contao\StringUtil;
use Contao\DataContainer;
use Alnv\ContaoCleverreachApi\API\Cleverreach;

class Form
{

    public function getForms(): array
    {

        $arrReturn = [];
        $objCleverreachApi = new Cleverreach();
        $arrGroups = $objCleverreachApi->getForms();

        foreach ($arrGroups as $arrGroup) {
            $arrReturn[$arrGroup['value']] = $arrGroup['label'];
        }

        return $arrReturn;
    }

    public function setCleverreachGroups(DataContainer $dc): void
    {

        if (Input::get('act') == 'edit') {

            $objField = Database::getInstance()->prepare('SELECT * FROM tl_form_field WHERE id = ?')->limit(1)->execute($dc->id);
            $arrOptions = StringUtil::deserialize($objField->options, true);

            if (!$objField->setCleverreachNewsletter) {
                return;
            }

            if (empty($arrOptions) || isset($arrOptions[0]) && $arrOptions[0] == '') {

                $objCleverreachApi = new Cleverreach();
                $arrOptions = \serialize($objCleverreachApi->getGroups());
                Database::getInstance()->prepare('UPDATE tl_form_field %s WHERE id = ?')->set(['tstamp' => time(), 'options' => $arrOptions])->limit(1)->execute($dc->id);
            }
        }
    }
}