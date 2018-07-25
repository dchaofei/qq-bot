<?php


namespace QqBot\Extension;


class TuLing
{
    private static $apiKey = "";
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
        file_put_contents('tmp/tuling.log', $res . "\n\n", FILE_APPEND);
        $res = json_decode($res, true);

        //if (isset($res['intent']['code']) && strpos($res['intent']['code'], '4000') === false) {
        if (isset($res['intent']['code'])) {
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

            print_r([implode("\n", array_slice($str, 0, 8))]);
            return [implode("\n", array_slice($str, 0, 8))];
        }

        return json_encode($res);
    }
}