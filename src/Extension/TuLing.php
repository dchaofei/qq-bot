<?php


namespace QqBot\Extension;


class TuLing
{
    private static $apiKey = "992acd97801e4541a3dbd1a0e478c8ba";
    private static $userId = "1";

    const URL = "http://openapi.tuling123.com/openapi/api/v2";

    public static function request($text)
    {
        $params = [
            'reqType' => 0,
            'userInfo' => [
                'apiKey' => static::$apiKey,
                'userId' => static::$userId
            ],
            'perception' => [
                'inputText' => [
                    'text' => $text,
                ],
            ],
        ];

        $ch = curl_init(self::URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        $res = curl_exec($ch);
        $res = json_decode($res, true);
        print_r($res);

        if (isset($res['intent']['code']) && strpos($res['intent']['code'], '4000') === false) {
            foreach ($res['results'] as $key => $value) {
                if ($value['resultType'] == 'news') {
                    foreach ($value['values']['news'] as $item) {
                        $str[] = array_shift($item);
                    }
                }

//                if ($value['resultType'] == 'text') {
//                    $str['text'] = $value['values']['text'] . "\n";
//                }

                $str[] = implode("\n", $value['values']);
            }


            return [implode("\n", array_slice($str, 0, 8))];
        }

        return json_encode($res);
    }
}