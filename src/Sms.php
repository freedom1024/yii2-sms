<?php
/**
 * User: Ryan
 * Date: 2017/10/23
 * Time: 15:24
 */

namespace ryan\yii\sms;

use yii\base\Component;

/**
 * Yii2 short message
 * Class Sms
 * @package common\components
 */
class Sms extends BaseSms implements SmsInterface
{
    /**
     * @event SmsEvent
     */
    const EVENT_BEFORE_SEND = 'beforeSend';
    /**
     * @event SmsEvent
     */
    const EVENT_AFTER_SEND = 'afterSend';

    /**
     * @var $url string api url
     */
    public $url;

    /**
     * @var $data array post data
     */
    public $data;

    /**
     * send sms
     * @param array $data post data
     * @param array $params extra params
     * @return array|mixed
     */
    public function send($data = [], $params = [])
    {
        $this->trigger(self::EVENT_BEFORE_SEND, new SmsEvent([
            'params' => $params,
        ]));

        $httpClient = \Yii::$app->http;
        $request = $httpClient->post($this->url, $data);
        $send = $httpClient->send($request);
        $response = $send->getData();

        $this->trigger(self::EVENT_AFTER_SEND, new SmsEvent([
            'response' => $response
        ]));

        return $response;
    }

    /**
     * query account balance
     * @return array|mixed
     */
    public function queryBalance($data)
    {
        $httpClient = \Yii::$app->http;

        $request = $httpClient->post($this->url, $data);
        $response = $httpClient->send($request);
        $response = $response->getData();

        return $response;
    }
}