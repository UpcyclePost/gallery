<?php

class Helpers {
    public static function pretty($argument)
    {
        if (is_float($argument)) return round($argument, 2);
        elseif (strpos($argument, '.') !== false) return number_format($argument, 2);
        return number_format($argument);
    }

    public static function tokenTruncate($string, $desiredLength, $ellipsis = \false)
    {
        $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
        $parts_count = count($parts);

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part)
        {
            $length += strlen($parts[ $last_part ]);
            if ($length > $desiredLength)
            {
                break;
            }
        }

        $result = trim(implode(array_slice($parts, 0, $last_part)));

        if ($ellipsis && strlen($result) < strlen(trim($string)))
        {
            $result = sprintf('%s...', $result);
        }

        return $result;
    }

    public static function dateFormat($argument)
    {
        return date('n/d/Y g:i a', strtotime($argument));
    }

    public static function recency($date)
    {
        $carbon = new Carbon\Carbon($date);

        // If it's been around five minutes, let's just say now
        // This accounts for weird time differences between servers
        if ($carbon->diffInMinutes() <= 5)
        {
            return 'just now';
        }

        return $carbon->diffForHumans();
    }

    public static function truncate($string, $length = 45)
    {
        if (strlen($string) >= $length)
        {
            $string = sprintf('%s...', trim(substr($string, 0, ($length-3))));
        }

        return htmlentities($string, ENT_QUOTES, 'ISO-8859-1');
    }

    public static function url($argument) {
        return strtolower(urlencode(preg_replace('/[^a-zA-Z0-9]/', '-', $argument)));
    }

    public static function getImageUrl($path)
    {
        $url = \Phalcon\DI::getDefault()->get('imageUrl');

        return $url->get($path);
    }

    public static function createShortCode($argument)
    {
        $chars = "123456789bcdfghjkmnpqrstvwxyzBCDFGHJKLMNPQRSTVWXYZ";
        $length = strlen($chars);

        $id = intval($argument);

        $code = '';

        while ($id > $length - 1)
        {
            $code = $chars[intval(fmod($id, $length))] . $code;
            $id = floor($id / $length);
        }

        $code = $chars[intval($id)] . $code;

        return $code;
    }

    public static function getCategoryList() {
        if (xcache_isset('categories')) {
            return xcache_get('categories');
        } else {
            $categories = [];
            foreach (Category::find(['order' => 'title ASC']) AS $category) {
                $categories[$category->slug] = ['title' => $category->title, 'ik' => $category->ik];
            }
            xcache_set('categories', $categories, 3600);

            return $categories;
        }
    }

    public static function getMaterials($tags) {
        $materials = Array('copper' => null,'metal' => null,'wood' => null,'aluminum' => null,'plastic' => null,'paper' => null,'bronze' => null,'glass' => null);
        $foundMaterials = [];

        foreach ($tags AS $tag) {
            if (array_key_exists(strtolower($tag), $materials)) {
                $foundMaterials[] = $tag;
            }
        }

        return $foundMaterials;
    }

    public static function sendEmail($to, $subject, $body, $fromName = 'UpcyclePost', $fromAddress = 'up@upcyclepost.com') {
        $config = Phalcon\DI::getDefault()->get('config');

        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->Host = $config->SES->smtp->host;
        $mail->Port = $config->SES->smtp->port;
        $mail->SMTPSecure = 'tls';
        $mail->Username = $config->SES->smtp->username;
        $mail->Password = $config->SES->smtp->password;

        $mail->SetFrom($fromAddress, $fromName);
        $mail->AddReplyTo($fromAddress, $fromName);

        $mail->Subject = $subject;
        $mail->MsgHTML($body);
        $mail->AddAddress($to);

        $result = $mail->Send();

        return $result;
    }

    public static function dsq_hmacsha1($data, $key) {
        $blocksize=64;
        $hashfunc='sha1';
        if (strlen($key)>$blocksize)
            $key=pack('H*', $hashfunc($key));
        $key=str_pad($key,$blocksize,chr(0x00));
        $ipad=str_repeat(chr(0x36),$blocksize);
        $opad=str_repeat(chr(0x5c),$blocksize);
        $hmac = pack(
            'H*',$hashfunc(
                ($key^$opad).pack(
                    'H*',$hashfunc(
                        ($key^$ipad).$data
                    )
                )
            )
        );
        return bin2hex($hmac);
    }

    public static function UAIsBot() {
        if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
            return true;
        } else {
            return false;
        }
    }

    public static function isRestrictedDomain($input)
    {
        if (substr($input, 0, 4) != 'http')
        {
            $parsedUrl = parse_url(sprintf('http://%s', $input), \PHP_URL_HOST);
        }
        else
        {
            $parsedUrl = parse_url($input, \PHP_URL_HOST);
        }

        return stripos($parsedUrl, 'etsy.com') !== false;
    }
}
