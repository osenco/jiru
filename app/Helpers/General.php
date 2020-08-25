<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Config;
use Stichoza\GoogleTranslate\GoogleTranslate;

if (!function_exists('send_sms')) {
    function send_sms($phones = null, $message = '', $provider = 'at', $schedule = null)
    {
        if ($provider == 'at') {
            try {
                $username = settings("notifications_sms", 'at_username', 'think_organic');
                $api_key  = settings("notifications_sms", 'at_key', '9d2d7ff4fe9eebcfcc12d5fc7a3632bef1756be61835c13fa1b728ab3a1d28d9');
                $from     = settings("notifications_sms", 'at_shortcode', 'ORGANICS_KE');
                $AT       = new AfricasTalking($username, $api_key);
                $sms      = $AT->sms();

                return $sms->send(
                    [
                        'to'      => $phones,
                        'message' => $message,
                        'from'    => $from,
                    ]
                );
            } catch (\Throwable $th) {
                return [
                    'status'  => 'failed',
                    'message' => $th->getMessage(),
                ];
            }
        } else {
            $headers = [
                "AccessKey" => settings('notifications_sms', 'onfon_access_key', str_random(32)),
                "Content-Type" => "application/json",
            ];

            $client = new Client([
                "base_uri" => "https://api.onfonmedia.co.ke",
                "timeout"  => 0,
                "headers" => $headers
            ]);

            $messages = [];
            foreach ($phones as $phone) {
                $messages[] = [
                    "Number" => $phone,
                    "Text" => $message
                ];
            }

            $payload = [
                "SenderId" => settings('notifications_sms', 'onfon_shortcode', 'PESSA'),
                "IsUnicode" => true,
                "IsFlash" => true,
                "MessageParameters" => $messages,
                "ApiKey" => settings('notifications_sms', 'onfon_api_key', base64_encode(str_random(6))),
                "ClientId" => settings('notifications_sms', 'onfon_client_id')
            ];

            is_null($schedule) ? "" : $payload["ScheduleDateTime"] = $schedule; //yyyy-MM-dd HH:MM

            try {
                $req = $client->post(
                    "/v1/sms/SendBulkSMS",
                    [
                        "json" => $payload,
                        "headers" => [
                            "AccessKey" => settings('notifications_sms', 'onfon_access_key', str_random(32)),
                            "Content-Type" => "application/json",
                        ]
                    ]
                );

                $res = $req->getBody()->getContents();
                $response = json_decode($res, true);
                $payload = [];

                if (isset($response["Data"]) && is_array($response["Data"])) {
                    foreach ($response["Data"] as $message) {
                        $payload[] = array(
                            "buupass_message_id" => time(),
                            "message_id" => $message["MessageId"],
                            "status" => $message["MessageErrorDescription"],
                            "status_code" => ($message["MessageErrorCode"] == 0) ? 200 : 400
                        );

                        if ($message["MessageErrorDescription"] !== "Success") {
                            $payload['errors'] = [];
                            $payload['errors']['data'] = [];
                            $payload['errors']['status'] = 400;
                            $payload['errors']['detail'] = "Message sending failure by Onfon";
                            $payload['errors']['failures'][]  = "+{$message['MobileNumber']}";
                        }
                    }
                } else {
                    if (isset($response["ErrorDescription"])) {
                        return array('status' => 'failed', 'message' => $response["ErrorDescription"]);
                    } else {
                        return array('status' => 'failed', 'message' => $response["ErrorCode"] . ' ' . $response["ErrorDescription"]);
                    }
                }

                return $payload;
            } catch (\Throwable $th) {
                return array('status' => 'failed', 'message' => $th->getMessage());
            }
        }
    }
}

if (!function_exists('onfon_balance')) {
    function onfon_balance()
    {
        try {
            $headers = [
                "AccessKey" => settings('notifications_sms', 'onfon_access_key', str_random(32)),
                "Content-Type" => "application/json",
            ];

            $client = new Client([
                "base_uri" => "https://api.onfonmedia.co.ke",
                "timeout"  => 0,
                "headers" => $headers
            ]);
            $response = $client->get(
                "/v1/sms/Balance",
                [
                    "query" => [
                        "ApiKey" => settings('notifications_sms', 'onfon_api_key', base64_encode(str_random(6))),
                        "ClientId" => settings('notifications_sms', 'onfon_client_id')
                    ],
                    "headers" => [
                        "AccessKey" => settings('notifications_sms', 'onfon_access_key', str_random(32)),
                        "Content-Type" => "application/json",
                    ]
                ]
            );

            return response()->json($response);
        } catch (\Throwable $th) {
            return array('status' => 'failed', 'message' => $th->getMessage());
        }
    }
}

if (!function_exists('send_email')) {
    function send_email($email = null, $name = '', $data = '', $subject = '', $attachments = [], $type = 'html')
    {
        $from_address = settings("notifications_mail", 'email', config('mail.from.address', 'pessa@gmail.com'));
        $from_name    = settings("notifications_mail", 'name', config('app.name', 'Pessa'));

        $view = ($type == 'html') ? 'emails.generic' : ['text' => 'mail'];

        return Mail::send($view, ['message' => $data], function ($message) use ($email, $name, $data, $subject, $attachments, $from_address, $from_name) {
            $message->to($email, $name)->subject($subject);

            if (!empty($attachments)) {
                foreach ($attachments as $file) {
                    $message->attach($file);
                }
            }

            $message->from($from_address, $from_name);
        });
    }
}

if (!function_exists('send_follow_up_text')) {
    function send_follow_up_text($user, $data = [])
    {
        if (settings('notifications_sms', 'after_sale_sms', 'no') == 'yes') {
            $message           = settings('notifications_sms', 'after_sale_sms_msg', 'Hi {user}. {company} appreciates your payment of KSH {amount}.');
            $formatted_message = trim($message);
            $formatted_message = str_replace('{user}', $user->name, $formatted_message);

            if (!empty($data)) {
                foreach ($data as $item => $value) {
                    $formatted_message = str_replace("{" . $item . "}", $value, $formatted_message);
                }
            }

            $customized_message = str_replace('{company}', settings("general", "name", config("app.name", "Pessa")), $formatted_message);

            if (settings('notifications_sms', 'after_sale_feedback', 'no') == 'yes') {
                $feedback = str_replace('{phone}', settings("general", "phone", "0204404993"), settings('notifications_sms', 'after_sale_feedback_msg'));
                $customized_message .= " {$feedback}";
            }

            return send_sms($user->phone, $customized_message);
        }
    }
}

if (!function_exists('ke_counties')) {
    function ke_counties($index = null)
    {
        $counties = array(
            "BAR" => "Baringo",
            "BMT" => "Bomet",
            "BGM" => "Bungoma",
            "BSA" => "Busia",
            "EGM" => "Elgeyo-Marakwet",
            "EBU" => "Embu",
            "GSA" => "Garissa",
            "HMA" => "Homa Bay",
            "ISL" => "Isiolo",
            "KAJ" => "Kajiado",
            "KAK" => "Kakamega",
            "KCO" => "Kericho",
            "KBU" => "Kiambu",
            "KLF" => "Kilifi",
            "KIR" => "Kirinyaga",
            "KSI" => "Kisii",
            "KIS" => "Kisumu",
            "KTU" => "Kitui",
            "KLE" => "Kwale",
            "LKP" => "Laikipia",
            "LAU" => "Lamu",
            "MCS" => "Machakos",
            "MUE" => "Makueni",
            "MDA" => "Mandera",
            "MAR" => "Marsabit",
            "MRU" => "Meru",
            "MIG" => "Migori",
            "MBA" => "Mombasa",
            "MRA" => "Muranga",
            "NBO" => "Nairobi",
            "NKU" => "Nakuru",
            "NDI" => "Nandi",
            "NRK" => "Narok",
            "NYI" => "Nyamira",
            "NDR" => "Nyandarua",
            "NER" => "Nyeri",
            "SMB" => "Samburu",
            "SYA" => "Siaya",
            "TVT" => "Taita Taveta",
            "TAN" => "Tana River",
            "TNT" => "Tharaka-Nithi",
            "TRN" => "Trans-Nzoia",
            "TUR" => "Turkana",
            "USG" => "Uasin Gishu",
            "VHG" => "Vihiga",
            "WJR" => "Wajir",
            "PKT" => "West Pokot",
        );

        return is_null($index) ? $counties : $counties[$index];
    }
}

if (!function_exists('ke_cities')) {
    function ke_cities($index = null)
    {
        $cities = array(
            'Baragoi',
            'Bungoma',
            'Busia',
            'Butere',
            'Dadaab',
            'Diani Beach',
            'Eldoret',
            'Emali',
            'Embu',
            'Garissa',
            'Gede',
            'Hola',
            'Homa Bay',
            'Isiolo',
            'Kitui',
            'Kibwezi',
            'Kajiado',
            'Kakamega',
            'Kakuma',
            'Kapenguria',
            'Kericho',
            'Keroka',
            'Kiambu',
            'Kilifi',
            'Kisii',
            'Kisumu',
            'Kitale',
            'Lamu',
            'Langata',
            'Litein',
            'Lodwar',
            'Lokichoggio',
            'Londiani',
            'Loyangalani',
            'Machakos',
            'Makindu',
            'Malindi',
            'Mandera',
            'Maralal',
            'Marsabit',
            'Meru',
            'Mombasa',
            'Moyale',
            'Mumias',
            'Muranga',
            'Mutomo',
            'Nairobi',
            'Naivasha',
            'Nakuru',
            'Namanga',
            'Nanyuki',
            'Naro Moru',
            'Narok',
            'Nyahururu',
            'Nyeri',
            'Ruiru',
            'Shimoni',
            'Takaungu',
            'Thika',
            'Vihiga',
            'Voi',
            'Wajir',
            'Watamu',
            'Webuye',
            'Wote',
            'Wundanyi',
        );

        return is_null($index) ? $cities : $cities[$index];
    }
}

if (!function_exists('months')) {
    function months($index = null)
    {
        $months = [
            'Select month', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
        ];

        return is_null($index) ? $months : $months[$index];
    }
}

if (!function_exists('active_module')) {
    function active_module($module)
    {
        return (isset(settings('modules', 'enabled')[$module]) && (settings('modules', 'enabled')[$module] == 'yes'));
    }
}


if (!function_exists('_e')) {
    function _e($string)
    {
        return $string;
        $tr = new GoogleTranslate();
        return $tr->setSource()->setTarget(user()->lang ?? settings("general", "locale", Config::get('app.locale', 'en')))->translate($string);
    }
}
if (!function_exists('___')) {
    function ___($string)
    {
        try {
            $tr = new GoogleTranslate();
            return $tr->setSource()->setTarget(user()->lang ?? settings("general", "locale", Config::get('app.locale', 'en')))->translate($string);
        } catch(Throwable $th){
            return $string;
        }
    }
}
