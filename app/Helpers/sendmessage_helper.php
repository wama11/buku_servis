<?php

if (!function_exists('send_message')) {
    function send_message($phone_number, $otp, $tokenidwa)
    {
        if (!function_exists('curl_init')) {
            log_message('error', 'cURL is not enabled on this server.');
            return null;
        }

        // $token = 'UyUzKi1TXGt2IysidC5LVVknTU5beV1rLHNyWz5G';

        // Load token from config
        $config = config('App');
        $token = $config->waApiToken;
        $url = 'https://m2m.coster.id/v1/efb8ec3a455011eeab21eb2138e54e5e/messages';

        $payload = '{
            "xid": "' . $tokenidwa . '",
            "to": "' . $phone_number . '",
            "type": "template",
            "template": {
                "name": "buku_servis",
                "language": {
                    "policy": "deterministic",
                    "code": "id"
                },
                "components": [
                        {
                                "type": "body",
                                "parameters": [{
                                    "type": "text",
                                    "text": "' . $otp . '"
                                }
                                ]
                            },{
                            "type" : "button",
                            "sub_type" : "url",
                            "index" : 0,
                            "parameters": [{
                                    "type": "text",
                                    "text": "' . $otp . '"
                            }]}
                            ]
            }
        }';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_ENCODING, "");
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt(
            $curl,
            CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $token
            )
        );
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $result = curl_exec($curl);
        curl_close($curl);

        $data = json_decode($result, true);

        if (isset($data["messages"]) && is_array($data["messages"])) {
            $message = $data["messages"];
            foreach ($message as $key => $value) {
                if (isset($value['xid'])) {
                    return $value['xid']; // Return xid if available
                }
            }
        }
        return null;
    }
}


// if (!function_exists('send_message')) {
//     function send_message(string $phone_number, string $otp, string $tokenidwa): ?string
//     {
//         // Check if cURL is enabled
//         if (!function_exists('curl_init')) {
//             log_message('error', 'cURL is not enabled on this server.');
//             return null;
//         }

//         // Load token from config
//         $config = config('App');
//         $token = $config->waApiToken;

//         $url = 'https://m2m.coster.id/v1/efb8ec3a455011eeab21eb2138e54e5e/messages';

//         // Safely encode payload as JSON
//         $payload = json_encode([
//             "xid" => $tokenidwa,
//             "to" => $phone_number,
//             "type" => "template",
//             "template" => [
//                 "name" => "buku_servis",
//                 "language" => [
//                     "policy" => "deterministic",
//                     "code" => "id"
//                 ],
//                 "components" => [
//                     [
//                         "type" => "body",
//                         "parameters" => [
//                             ["type" => "text", "text" => $otp]
//                         ]
//                     ],
//                     [
//                         "type" => "button",
//                         "sub_type" => "url",
//                         "index" => 0,
//                         "parameters" => [
//                             ["type" => "text", "text" => $otp]
//                         ]
//                     ]
//                 ]
//             ]
//         ]);

//         // Initialize cURL
//         $curl = curl_init();

//         curl_setopt_array($curl, [
//             CURLOPT_URL => $url,
//             CURLOPT_RETURNTRANSFER => true,
//             CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//             CURLOPT_CUSTOMREQUEST => "POST",
//             CURLOPT_POSTFIELDS => $payload,
//             CURLOPT_HTTPHEADER => [
//                 'Content-Type: application/json',
//                 'Authorization: Bearer ' . $token
//             ],
//             CURLOPT_SSL_VERIFYHOST => 2, // Set to 0 only for local/test env
//             CURLOPT_SSL_VERIFYPEER => true, // Set to false only in testing
//         ]);

//         $result = curl_exec($curl);

//         // Handle cURL error
//         if ($result === false) {
//             log_message('error', 'cURL Error: ' . curl_error($curl));
//             curl_close($curl);
//             return null;
//         }

//         curl_close($curl);

//         $data = json_decode($result, true);

//         // Check for valid response
//         if (isset($data['messages']) && is_array($data['messages'])) {
//             foreach ($data['messages'] as $msg) {
//                 if (isset($msg['xid'])) {
//                     return $msg['xid'];
//                 }
//             }
//         } else {
//             log_message('error', 'Unexpected WhatsApp API response: ' . $result);
//         }

//         return null;
//     }
// }
