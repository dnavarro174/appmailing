<?php
namespace App\Models;
class LabsMobileService{

    public static function send($phone_number, $message, $from = '', $username ='', $password=''){
        return self::sendPostStream($phone_number, $message, $from, $username, $password);
    }

    public static function sendGetCurl($phone_number, $message, $from = '', $username ='', $password='') {
        if($username == '')$username = env('LABSMOBILE_USERNAME');
        if($password == '')$password = env('LABSMOBILE_TOKEN');
        $message = urlencode($message);
        $url = "http://api.labsmobile.com/get/send.php?username={$username}&password={$password}&msisdn={$phone_number}&message={$message}&sender={$from}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        $response = curl_exec($ch);
        $err = curl_error($ch);
        $ok = !!!$err;
        return compact('ok', 'response','err','url');
    }

    public static function sendPostCurl($phone_number, $message, $from = '', $username ='', $password='') {
        $recipient = [
            ["msisdn"=>$phone_number]
        ];
        $data = [
            "message" => $message,
            "tpoa" => $from,
            "recipient" => $recipient
        ];
        if($username == '')$username = env('LABSMOBILE_USERNAME');
        if($password == '')$password = env('LABSMOBILE_TOKEN');
        $user = $username . ':' . $password;

        $data_json = json_encode($data);

        $auth_basic = base64_encode($user);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.labsmobile.com/json/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            //CURLOPT_POSTFIELDS => '{"message":"Text of the SMS message", "tpoa":"Sender","recipient":[{"msisdn":"12015550123"},{"msisdn":"447400123456"},{"msisdn":"5212221234567"}]}',
            CURLOPT_POSTFIELDS => $data_json,
            CURLOPT_HTTPHEADER => array(
                "Authorization: Basic ".$auth_basic,
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $ok = !!!$err;
        return compact('ok', 'response','err', 'data_json');
    }
    public static function sendPostStream($phone_number, $message, $from = '', $username ='', $password='') {
        #$message = '-> '.$message;
        $message = $message;
        $recipient = [
            ["msisdn"=>$phone_number]
        ];
        $payload = [
            "message" => $message,
            "tpoa" => $from,
            "recipient" => $recipient
        ];
        if($username == '')$username = env('LABSMOBILE_USERNAME');
        if($password == '')$password = env('LABSMOBILE_TOKEN');
        $user = $username . ':' . $password;

        $data = json_encode($payload,JSON_UNESCAPED_SLASHES);
        //$data = http_build_query($payload);
        $auth_basic = base64_encode($user);
        #dd(compact('auth_basic', 'user'));
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Authorization: Basic {$auth_basic}\r\n".
                    "Cache-Control: no-cache\r\n".
                    "content-type: application/json\r\n",
                'content' => $data,
                'ignore_errors' => true//ADDED
            ]
        ];
        $url = 'https://api.labsmobile.com/json/send';
        $context = stream_context_create($options);
        // Send a request
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result, true);
        $code = $response['code'] ?? 0;
        $message = $response['message'] ?? '';
        $ok = true;
        $response['req'] = $data;
        $response['ok'] = $code == 0 && strstr($message,'Message has been successfully sent.');
        $response['user'] = $user;
        return $response;
    }

}
