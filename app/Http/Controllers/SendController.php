<?php

namespace App\Http\Controllers;

use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Client\LongLivedAccessToken;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Exceptions\AmoCRMoAuthApiException;
use AmoCRM\Models\CustomFieldsValues\CheckboxCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\CheckboxCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\CheckboxCustomFieldValueModel;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Illuminate\Http\Request;

/**
 * @class SendController
 *
 */
class SendController extends Controller
{
    /** CRM id клиента
     * @const clientId
     */
    const CLIENT_ID = '5c5d88b9-7ec9-4a75-8281-606106dc29a3';
    /** Секретный ключ клиента
     * @const clientSecret
     */
    const CLIENT_SECRET = '1UyyUxXq9TOqqV0BKJWKiTa6XYhKmntEWe0YaP6aRpaeNArXt1netJkElfEbBtHc';
    /** URI клиента для создания сделки
     * @const redirectUri
     */
    const REDIRECT_URI = 'http://crm.ru';
    /** Домен клиента для создания сделки
     * @const domain
     */
    const DOMAIN = 'dudinivan15.amocrm.ru';
    /** Долгоживущий токен клиента для создания сделки
     * @const accessToken
     */
    const ACCESS_TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjFkZGE0ZmJmZWIxNmEzNzk0ZWU1MGJhMDdiMTc1ZjE3ZDExYzEzMjA4OTY1ZTU5YzlmZGY4Yjk0ZjU3ZTU5MGQ0ZDA3MWM1NjExN2UwYTQ0In0.eyJhdWQiOiI1YzVkODhiOS03ZWM5LTRhNzUtODI4MS02MDYxMDZkYzI5YTMiLCJqdGkiOiIxZGRhNGZiZmViMTZhMzc5NGVlNTBiYTA3YjE3NWYxN2QxMWMxMzIwODk2NWU1OWM5ZmRmOGI5NGY1N2U1OTBkNGQwNzFjNTYxMTdlMGE0NCIsImlhdCI6MTcxNDIwMzA5NSwibmJmIjoxNzE0MjAzMDk1LCJleHAiOjE3MzE1NDI0MDAsInN1YiI6IjEwOTkyMjcwIiwiZ3JhbnRfdHlwZSI6IiIsImFjY291bnRfaWQiOjMxNzI0NTE4LCJiYXNlX2RvbWFpbiI6ImFtb2NybS5ydSIsInZlcnNpb24iOjIsInNjb3BlcyI6WyJjcm0iLCJmaWxlcyIsImZpbGVzX2RlbGV0ZSIsIm5vdGlmaWNhdGlvbnMiLCJwdXNoX25vdGlmaWNhdGlvbnMiXSwiaGFzaF91dWlkIjoiYTMzNjc1NzEtNzNhNS00MGE1LWJhMjMtMjMzMTQ5NzcwM2U0In0.S9pGqupzcRcfHPRj5dcSMs4DDkIDFTBq_lUb9JvKPjBGeo6hWCmzj2RTaJ_kX_-Yc9W9HxqdS6WlNHHE5A-0D-GncqOZaadVU0etCAi-anHVcUX7OaGnaAlEsIll5KT5iApFSHJrtXm5LuSfkJ77rimqAiExtDneZhl4aaSzxzSKzFoyR-aRSlUP3PuFf0o48NDnJ-sM_9Y8GDeMaiuCB8Ru86ZqYw6EkYv4ekMO8JSp_vsw6_hqvpKlowdOZdxuDoioEcvTWwrkG66A3c4T05OGvrFR0IWoBC0zpZn8Es7zcqfBe5Po5oYqMz_zBZm_jN7ETvqjRsK9j84UHxQUmg';
    /** Id поля имени в сделке
     * @const nameId
     */
    const NAME_ID = 222599;
    /** Id поля почты в сделке
     * @const email
     */
    const EMAIL_ID = 222601;
    /** Id поля номера телефона в сделке
     * @const phoneId
     */
    const PHONE_ID = 222603;
    /** Id поля цены в сделке
     * @const priceId
     */
    const PRICE_ID = 222587;
    /** Id поля времени на сайте в сделке
     * @const spendTimeId
     */
    const SPEND_TIME_ID = 222589;

    /** Основная функция для отправки сделки в crm
     * @param Request $request
     * @return Application|Factory|View|RedirectResponse
     */
    public function send(Request $request)
    {
        try {
            $formData  = $request->all();
            $timeToLog = $request->session()->get('_timeToLog');
            date_default_timezone_set('Europe/Moscow');
            $timeNow      = date('d-m-Y H:i:s');
            $timeToLog    = strtotime($timeToLog);
            $timeNow      = strtotime($timeNow);
            $externalData = $this->getExternalData($formData);
            if (!$this->checkData($externalData)) {
                return redirect()->back();
            }
            if ($timeNow - $timeToLog > 30) {
                $externalData[self::SPEND_TIME_ID] = 1;
            }

            $apiClient              = $this->connectToCRM();
            $leadsService           = $apiClient->leads();
            $lead                   = new LeadModel();
            $leadCustomFieldsValues = new CustomFieldsValuesCollection();
            foreach ($externalData as $id => $value) {
                $leadCustomFieldsValues->add($this->sendLead($value, $id));
                $lead->setCustomFieldsValues($leadCustomFieldsValues);
            }
            $lead->setName($externalData[self::NAME_ID]);
            $leadsService->addOne($lead);
        } catch (AmoCRMoAuthApiException|AmoCRMApiException $e) {
            redirect()->back()->withErrors([$e->getMessage()]);
        }
        return view('send');
    }

    /** Возвращает данные из формы в виде массива
     *
     * @param array $formData
     * @return array
     */
    public function getExternalData(array $formData): array
    {
        return
            [
                self::PHONE_ID      => $formData['phone'],
                self::PRICE_ID      => $formData['price'],
                self::NAME_ID       => $formData['name'],
                self::EMAIL_ID      => $formData['email'],
                self::SPEND_TIME_ID => 0

            ];
    }

    /** Производит подключание к crm
     *
     * @return AmoCRMApiClient
     */
    public function connectToCRM(): AmoCRMApiClient
    {
        $apiClient            = new AmoCRMApiClient(self::CLIENT_ID, self::CLIENT_SECRET, self::REDIRECT_URI);
        $longLivedAccessToken = new LongLivedAccessToken(self::ACCESS_TOKEN);

        $apiClient->setAccessToken($longLivedAccessToken)
            ->setAccountBaseDomain(self::DOMAIN)
            ->onAccessTokenRefresh(
                function (AccessTokenInterface $accessToken, string $baseDomain) {
                    saveToken(
                        [
                            'accessToken'  => $accessToken->getToken(),
                            'refreshToken' => $accessToken->getRefreshToken(),
                            'expires'      => $accessToken->getExpires(),
                            'baseDomain'   => $baseDomain,
                        ]
                    );
                }
            );
        return $apiClient;
    }

    /** Функция добавления полей с данными в сделку
     * @param $data
     * @param $id
     * @return CheckboxCustomFieldValuesModel|TextCustomFieldValuesModel
     */
    public function sendLead($data, $id)
    {
        if ((int)$id == self::SPEND_TIME_ID) {
            $textCustomFieldValueModel = new CheckboxCustomFieldValuesModel();
            $textCustomFieldValueModel->setFieldId((int)$id);
            $textCustomFieldValueModel->setValues(
                (new CheckboxCustomFieldValueCollection())
                    ->add((new CheckboxCustomFieldValueModel())->setValue($data)
                    ));
        } else {
            $textCustomFieldValueModel = new TextCustomFieldValuesModel();
            $textCustomFieldValueModel->setFieldId((int)$id);
            $textCustomFieldValueModel->setValues(
                (new TextCustomFieldValueCollection())
                    ->add((new TextCustomFieldValueModel())->setValue($data)
                    ));
        }
        return $textCustomFieldValueModel;
    }

    /** Функция, которая проводит валидацию данных
     * @param $data
     * @return bool
     */
    public function checkData($data)
    {
        if (!preg_match("/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/", $data[self::PHONE_ID])) {
            return false;
        }
        if (!preg_match("/^([a-z0-9_-]+\.)*[a-z0-9_-]+@[a-z0-9_-]+(\.[a-z0-9_-]+)*\.[a-z]{2,6}$/i", $data[self::EMAIL_ID])) {
            return false;
        }
        if (!preg_match("/^\d+$/", $data[self::PRICE_ID])) {
            return false;
        }
        return true;
    }
}
