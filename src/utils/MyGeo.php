<?php

namespace borpheus\utils;

use borpheus\utils\MyHelper;
use GuzzleHttp\Exception\ClientException;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use yii\web\Response;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Client;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use Yandex\Geo;


class MyGeo
{
    public static function getCoordinates($address): array
    {
        try {

            $request = new Request('GET', '1.x');
            $client = new \GuzzleHttp\Client([
                'base_uri' => 'https://geocode-maps.yandex.ru/',
            ]);

            $response = $client->send($request, [
                'query' => [
                    'apikey' => $_ENV['YANDEX_JS_API_KEY'],
                    'geocode' => $address,
                    'format' => 'json',
                ],
            ]);

            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);

            if (is_array($response_data) && $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos']) {
                $coord = $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['Point']['pos'];
            } else {
                throw new Exception('Локация не найдена');
            }
            return [
                'success' => true,
                'coordinates' => ['points' => $coord],
            ];
        } catch (ClientException $e) {
            Yii::error('Client error: ' . $e->getMessage(), 'geo');
            return [
                'success' => false,
                'error' => 'Ошибка запроса: ' . $e->getMessage(),
            ];
        } catch (ServerException $e) {
            Yii::error('Server error: ' . $e->getMessage(), 'geo');
            return [
                'success' => false,
                'error' => 'Ошибка сервера: ' . $e->getMessage(),
            ];
        } catch (RequestException $e) {
            Yii::error('Request error: ' . $e->getMessage(), 'geo');
            return [
                'success' => false,
                'error' => 'Ошибка запроса: ' . $e->getMessage(),
            ];
        } catch (BadResponseException $e) {
            Yii::error('Bad response: ' . $e->getResponse()->getBody()->getContents(), 'geo');
            return [
                'success' => false,
                'error' => 'Неверный ответ сервера.',
            ];
        }
    }

    public static function getInfo($coordinates): array
    {
        try {
            $request = new Request('GET', '1.x');
            $client = new \GuzzleHttp\Client([
                'base_uri' => 'https://geocode-maps.yandex.ru/',
            ]);

            $response = $client->send($request, [
                'query' => [
                    'apikey' => $_ENV['YANDEX_JS_API_KEY'],
                    'geocode' => str_replace(' ', ',', $coordinates),
                    'format' => 'json',
                ],
            ]);

            $content = $response->getBody()->getContents();
            $response_data = json_decode($content, true);


            $city = '';
            if (is_array($response_data) && $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']) {
                $address = $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['text'];
                if (is_array($response_data) && $response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components']) {
                    foreach ($response_data['response']['GeoObjectCollection']['featureMember'][0]['GeoObject']['metaDataProperty']['GeocoderMetaData']['Address']['Components'] as $locationComp) {
                        if ($locationComp['kind'] === 'locality') {
                            $city = $locationComp['name'];
                        }
                    }
                }
            } else {
                throw new Exception('Локация не найдена');
            }
            return [
                'success' => true,
                'info' => ['city' => $city, 'address' => $address],
            ];
        } catch (ClientException $e) {
            Yii::error('Client error: ' . $e->getMessage(), 'geo');
            return [
                'success' => false,
                'error' => 'Ошибка запроса: ' . $e->getMessage(),
            ];
        } catch (ServerException $e) {
            Yii::error('Server error: ' . $e->getMessage(), 'geo');
            return [
                'success' => false,
                'error' => 'Ошибка сервера: ' . $e->getMessage(),
            ];
        } catch (RequestException $e) {
            Yii::error('Request error: ' . $e->getMessage(), 'geo');
            return [
                'success' => false,
                'error' => 'Ошибка запроса: ' . $e->getMessage(),
            ];
        } catch (BadResponseException $e) {
            Yii::error('Bad response: ' . $e->getResponse()->getBody()->getContents(), 'geo');
            return [
                'success' => false,
                'error' => 'Неверный ответ сервера.',
            ];
        }
    }
}