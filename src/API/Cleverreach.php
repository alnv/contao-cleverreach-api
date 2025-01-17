<?php

namespace Alnv\ContaoCleverreachApi\API;

use Contao\Config;
use Contao\System;
use Psr\Log\LogLevel;
use GuzzleHttp\Client;
use Contao\CoreBundle\Monolog\ContaoContext;

class Cleverreach
{

    protected string $strTokenType = '';

    protected string $strToken = '';

    public function __construct($arrOptions = [])
    {

        $objCurl = \curl_init();
        $arrOptions = $this->getOptions($arrOptions);
        \curl_setopt($objCurl, CURLOPT_URL, $arrOptions['cleverreachTokenUrl']);
        \curl_setopt($objCurl, CURLOPT_USERPWD, $arrOptions['cleverreachClientId'] . ":" . $arrOptions['cleverreachClientSecret']);
        \curl_setopt($objCurl, CURLOPT_POSTFIELDS, [
            'grant_type' => $arrOptions['cleverreachGrantType']
        ]);
        \curl_setopt($objCurl, CURLOPT_RETURNTRANSFER, true);
        $varResult = \curl_exec($objCurl);
        \curl_close($objCurl);

        if ($varResult) {
            $objResponse = \json_decode($varResult, true);
            $this->strToken = $objResponse['access_token'] ?? '';
            $this->strTokenType = $objResponse['token_type'] ?? '';
        }
    }

    public function subscribe($arrGroups, $arrSubscriber, $strFormId = ''): void
    {

        if (!is_array($arrGroups) || empty($arrGroups)) {

            System::getContainer()
                ->get('monolog.logger.contao')
                ->log(LogLevel::ERROR, 'Cleverreach API: No subscriber groups defined', ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);

            return;
        }

        $objRequest = new Client();
        foreach ($arrGroups as $strGroupId) {

            $objResponse = $objRequest->get('https://rest.cleverreach.com/v3/groups.json/' . $strGroupId, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => ucfirst($this->strTokenType) . ' ' . $this->strToken
                ]
            ]);

            if ($strContents = $objResponse->getBody()->getContents()) {

                $arrResponse = json_decode($strContents, true);

                if (isset($arrResponse['error']) && is_array($arrResponse['error'])) {

                    System::getContainer()
                        ->get('monolog.logger.contao')
                        ->log(LogLevel::ERROR, 'Cleverreach API: ' . ($arrResponse['error']['message'] ?? ''), ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);

                    continue;
                }
            }

            $objResponse = $objRequest->post('https://rest.cleverreach.com/v3/groups.json/' . $strGroupId . '/receivers', [
                'body' => \json_encode($arrSubscriber, 0, 512),
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => ucfirst($this->strTokenType) . ' ' . $this->strToken,
                    'Content-Type' => 'application/json'
                ]
            ]);

            if ($strContents = $objResponse->getBody()->getContents()) {

                $arrResponse = json_decode($strContents, true);

                if (isset($arrResponse['error']) && is_array($arrResponse['error'])) {

                    System::getContainer()
                        ->get('monolog.logger.contao')
                        ->log(LogLevel::ERROR, 'Cleverreach API: ' . ($arrResponse['error']['message'] ?? ''), ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);

                    continue;
                }

                System::getContainer()
                    ->get('monolog.logger.contao')
                    ->log(LogLevel::NOTICE, 'Cleverreach API: You have new subscriber', ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);

                if ($strFormId) {

                    $objResponse = $objRequest->post('https://rest.cleverreach.com/v3/forms.json/' . $strFormId . '/send/activate', [
                        'body' => \json_encode([
                            'email' => $arrResponse['email'],
                            'doidata' => [
                                "user_ip" => $_SERVER["REMOTE_ADDR"],
                                "referer" => $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"],
                                "user_agent" => $_SERVER["HTTP_USER_AGENT"]
                            ]
                        ], 0, 512),
                        'headers' => [
                            'Accept' => 'application/json',
                            'Authorization' => ucfirst($this->strTokenType) . ' ' . $this->strToken,
                            'Content-Type' => 'application/json'
                        ]
                    ]);

                    if ($strContents = $objResponse->getBody()->getContents()) {
                        $arrResponse = json_decode($strContents, true);
                        if (isset($arrResponse['error']) && is_array($arrResponse['error'])) {
                            System::getContainer()
                                ->get('monolog.logger.contao')
                                ->log(LogLevel::ERROR, 'Cleverreach API: ' . ($arrResponse['error']['message'] ?? ''), ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);
                        }
                    }
                }
            }
        }
    }

    public function getGroups(): array
    {

        $arrReturn = [];
        $objRequest = new Client();
        $objResponse = $objRequest->get('https://rest.cleverreach.com/v3/groups.json', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => ucfirst($this->strTokenType) . ' ' . $this->strToken
            ]
        ]);

        if ($strContents = $objResponse->getBody()->getContents()) {

            $arrResponse = json_decode($strContents, true);

            if (isset($arrResponse['error']) && is_array($arrResponse['error'])) {

                System::getContainer()
                    ->get('monolog.logger.contao')
                    ->log(LogLevel::ERROR, 'Cleverreach API: ' . ($arrResponse['error']['message'] ?? ''), ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);

                return $arrReturn;
            }

            if (is_array($arrResponse) && !empty($arrResponse)) {
                foreach ($arrResponse as $arrGroup) {
                    $arrReturn[] = [
                        'label' => $arrGroup['name'],
                        'value' => $arrGroup['id']
                    ];
                }
            }
        }

        return $arrReturn;
    }

    public function getForms(): array
    {

        $arrReturn = [];
        $objRequest = new Client();

        try {

            $objResponse = $objRequest->get('https://rest.cleverreach.com/v3/forms.json', [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Authorization' => ucfirst($this->strTokenType) . ' ' . $this->strToken
                ]
            ]);

            if ($strContents = $objResponse->getBody()->getContents()) {

                $arrResponse = json_decode($strContents, true);

                if (isset($arrResponse['error']) && is_array($arrResponse['error'])) {

                    System::getContainer()
                        ->get('monolog.logger.contao')
                        ->log(LogLevel::ERROR, 'Cleverreach API: ' . ($arrResponse['error']['message'] ?? ''), ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);

                    return $arrReturn;
                }

                if (is_array($arrResponse) && !empty($arrResponse)) {
                    foreach ($arrResponse as $arrForm) {
                        $arrReturn[] = [
                            'label' => $arrForm['name'],
                            'value' => $arrForm['id']
                        ];
                    }
                }
            }
        } catch (\Exception $objError) {

            System::getContainer()
                ->get('monolog.logger.contao')
                ->log(LogLevel::ERROR, $objError->getMessage(), ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);
        }

        return $arrReturn;
    }

    public function getAttributesByGroups($arrGroupIds): array
    {

        $arrReturn = [];
        if (empty($arrGroupIds) || !is_array($arrGroupIds)) {
            return $arrReturn;
        }

        foreach ($arrGroupIds as $strGroupId) {
            $arrReturn[$strGroupId] = $this->getAttributesByGroup($strGroupId);
        }

        return $arrReturn;
    }

    public function getAttributes(): array
    {

        $arrReturn = [];
        $objRequest = new Client();
        $objResponse = $objRequest->get('https://rest.cleverreach.com/v3/attributes.json', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => ucfirst($this->strTokenType) . ' ' . $this->strToken
            ]
        ]);

        if ($strContents = $objResponse->getBody()->getContents()) {

            $arrResponse = json_decode($strContents, true);

            if (isset($arrResponse['error']) && is_array($arrResponse['error'])) {

                System::getContainer()
                    ->get('monolog.logger.contao')
                    ->log(LogLevel::ERROR, 'Cleverreach API: ' . ($arrResponse['error']['message'] ?? ''), ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);

                return $arrReturn;
            }

            if (is_array($arrResponse) && !empty($arrResponse)) {
                foreach ($arrResponse as $arrAttribute) {
                    $arrReturn[$arrAttribute['name']] = $arrAttribute['description'];
                }
            }
        }

        return $arrReturn;
    }

    public function getAttributesByGroup($strGroupId): array
    {

        $arrReturn = [];

        $objRequest = new Client();
        $objResponse = $objRequest->get('https://rest.cleverreach.com/v3/groups.json/' . $strGroupId . '/attributes', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => ucfirst($this->strTokenType) . ' ' . $this->strToken
            ]
        ]);

        if ($strContents = $objResponse->getBody()->getContents()) {

            $arrResponse = json_decode($strContents, true);

            if (isset($arrResponse['error']) && is_array($arrResponse['error'])) {

                System::getContainer()
                    ->get('monolog.logger.contao')
                    ->log(LogLevel::ERROR, 'Cleverreach API: ' . ($arrResponse['error']['message'] ?? ''), ['contao' => new ContaoContext(__CLASS__ . '::' . __FUNCTION__)]);

                return $arrReturn;
            }

            if (is_array($arrResponse) && !empty($arrResponse)) {
                foreach ($arrResponse as $arrAttribute) {
                    $arrReturn[$arrAttribute['name']] = $arrAttribute['description'];
                }
            }
        }

        return $arrReturn;
    }

    protected function getOptions($arrOptions): array
    {

        $arrSettings = [
            'cleverreachTokenUrl' => Config::get('cleverreachTokenUrl'),
            'cleverreachClientId' => Config::get('cleverreachClientId'),
            'cleverreachClientSecret' => Config::get('cleverreachClientSecret'),
            'cleverreachGrantType' => 'client_credentials'
        ];

        foreach ($arrOptions as $strName => $strValue) {
            $arrSettings[$strName] = $strValue ?: $arrSettings[$strName];
        }

        return $arrSettings;
    }
}