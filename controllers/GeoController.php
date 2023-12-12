<?php

namespace app\controllers;

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
use borpheus\utils\MyGeo;

class GeoController extends Controller
{
    /**
     * @param $address
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function actionGetCoordinates($address): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return MyGeo::getCoordinates($address);
    }

}
