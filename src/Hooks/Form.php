<?php

namespace Alnv\ContaoCleverreachApi\Hooks;

use Alnv\ContaoCleverreachApi\API\Cleverreach;
use Contao\Environment;
use Contao\StringUtil;

class Form
{

    public function processFormData($arrPost, $arrForm, $arrFiles)
    {

        if ($arrForm['useCleverreachApi']) {

            $arrNewsletter = $arrPost['newsletter'];

            if (!is_array($arrNewsletter)) {
                $arrNewsletter = [$arrPost['newsletter']];
            }

            if (empty($arrNewsletter) || (isset($arrNewsletter[0]) && $arrNewsletter[0] == '')) {
                return;
            }

            $arrTags = [];
            $arrGlobalAttributes = [];

            if (isset($arrPost['tags']) && $arrPost['tags'] != '') {
                $arrTags = StringUtil::trimsplit(',', $arrPost['tags']);
                $arrTags = array_filter($arrTags, function ($strTag) {
                    if ($strTag == null || $strTag == '') {
                        return false;
                    }
                    return $strTag;
                });
                $arrTags = array_unique($arrTags);
            }

            $objCleverreachApi = new Cleverreach();
            $arrAttributes = $objCleverreachApi->getAttributes();

            if (!empty($arrAttributes)) {
                foreach ($arrAttributes as $strValue => $strLabel) {
                    if (isset($arrPost[$strValue])) {
                        $arrGlobalAttributes[$strValue] = $arrPost[$strValue];
                    }
                }
            }

            $objCleverreachApi->subscribe(
                $arrNewsletter,
                [
                    'activated' => 0,
                    'tags' => $arrTags,
                    'registered' => time(),
                    'email' => $arrPost['email'],
                    'source' => Environment::get('url'),
                    'global_attributes' => $arrGlobalAttributes
                ],
                $arrForm['cleverreachActiveFormId']
            );
        }
    }
}