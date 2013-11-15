<?php

/**
 * 实用函数
 */
class UtilHelper {

    const CHAR_MIX = 0;
    const CHAR_NUM = 1;
    const CHAR_WORD = 2;

    //获得客户端IP
    public static function getIp() {
        if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
            $ip = getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = null;
        return $ip;
    }

    /**
     * 读取远程文件内容，使用 curl 库或者 file_get_contents 函数，有超时设置
     * @param string $url
     * @param integer $timeout
     * @return string
     */
    public static function fileGetContents($url, $timeout = 10) {
        if (function_exists("curl_init")) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $fileContents = curl_exec($ch);
            curl_close($ch);
        } else if (ini_get("allow_url_fopen") == 1 || strtolower(ini_get("allow_url_fopen")) == "on") {
            $fileContents = file_get_contents($url);
        } else {
            $fileContents = "";
        }
        return $fileContents;
    }

    /**
     * 人民币小写转大写
     * @param integer $num
     * @return string
     */
    public static function num2rmb($num) {
        $c1 = "零壹贰叁肆伍陆柒捌玖";
        $c2 = "分角元拾佰仟万拾佰仟亿";
        $num = round($num, 2);
        $num = $num * 100;
        if (strlen($num) > 10) {
            return "oh,sorry,the number is too long!";
        }

        $i = 0;
        $c = "";
        while (1) {
            if ($i == 0) {
                $n = substr($num, strlen($num) - 1, 1);
            } else {
                $n = $num % 10;
            }

            $p1 = substr($c1, 3 * $n, 3);
            $p2 = substr($c2, 3 * $i, 3);
            if ($n != '0' || ($n == '0' && ($p2 == '亿' || $p2 == '万' || $p2 == '元' ))) {
                $c = $p1 . $p2 . $c;
            } else {
                $c = $p1 . $c;
            }

            $i = $i + 1;
            $num = $num / 10;
            $num = (int) $num;

            if ($num == 0) {
                break;
            }
        }
        $j = 0;
        $slen = strlen($c);
        while ($j < $slen) {
            $m = substr($c, $j, 6);

            if ($m == '零元' || $m == '零万' || $m == '零亿' || $m == '零零') {
                $left = substr($c, 0, $j);
                $right = substr($c, $j + 3);
                $c = $left . $right;
                $j = $j - 3;
                $slen = $slen - 3;
            }
            $j = $j + 2;
        }

        if (substr($c, strlen($c) - 3, 3) == '零') {
            $c = substr($c, 0, strlen($c) - 3);
        }

        if (substr($c, -3, 3) == '元') {
            return $c . "整";
        } else {
            return $c;
        }
    }

    /**
     * 求两个时间的时间差
     * @param $d1
     * @param $d2
     * @return integer
     */
    public static function dateDiff($d1, $d2) {
        if (is_string($d1))
            $d1 = strtotime($d1);
        if (is_string($d2))
            $d2 = strtotime($d2);
        return ($d2 - $d1) / 43200; //这里返回的是天数，如果除以3600就是返回小时了，依此类推
    }

//    static public function uuid() {
//        $being_timestamp = 1206576000; // 2008-03-27
//        $suffix_len = 3; //! 计算 ID 时要添加多少位随机数
//        $time = explode(' ', microtime());
//        $id = ($time[1] - $being_timestamp) . sprintf('%06u', substr($time[0], 2, 6));
//        if ($suffix_len > 0) {
//            $id .= substr(sprintf('%010u', mt_rand()), 0, $suffix_len);
//        }
//        return $id;
//    }

    /**
     * Generate a random UUID
     *
     * @see http://www.ietf.org/rfc/rfc4122.txt
     * @return RFC 4122 UUID
     * @static
     */
    public static function uuid() {
        $node = (isset($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : null;
        $pid = null;

        if (strpos($node, ':') !== false) {
            if (substr_count($node, '::')) {
                $node = str_replace('::', str_repeat(':0000', 8 - substr_count($node, ':')) . ':', $node);
            }
            $node = explode(':', $node);
            $ipv6 = '';

            foreach ($node as $id) {
                $ipv6 .= str_pad(base_convert($id, 16, 2), 16, 0, STR_PAD_LEFT);
            }
            $node = base_convert($ipv6, 2, 10);

            if (strlen($node) < 38) {
                $node = null;
            } else {
                $node = crc32($node);
            }
        } elseif (empty($node)) {
            $host = (isset($_ENV['HOSTNAME'])) ? $_ENV['HOSTNAME'] : null;

            if (empty($host)) {
                $host = (isset($_ENV['HOST'])) ? $_ENV['HOST'] : null;
            }

            if (!empty($host)) {
                $ip = gethostbyname($host);

                if ($ip === $host) {
                    $node = crc32($host);
                } else {
                    $node = ip2long($ip);
                }
            }
        } elseif ($node !== '127.0.0.1') {
            $node = ip2long($node);
        } else {
            $node = null;
        }

        if (empty($node)) {
            $node = crc32('DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');
        }

        if (function_exists('zend_thread_id')) {
            $pid = zend_thread_id();
        } elseif (function_exists('getmypid')) {
            $pid = getmypid();
        }

        if (!$pid || $pid > 65535) {
            $pid = mt_rand(0, 0xfff) | 0x4000;
        }

        list($timeMid, $timeLow) = explode(' ', microtime());
        $uuid = sprintf(
                "%08x-%04x-%04x-%02x%02x-%04x%08x", (int) $timeLow, (int) substr($timeMid, 2) & 0xffff, mt_rand(0, 0xfff) | 0x4000, mt_rand(0, 0x3f) | 0x80, mt_rand(0, 0xff), $pid, $node
        );

        return $uuid;
    }

    public static function generateSecret($len = 6, $type = self::CHAR_WORD) {
        $secret = '';
        for ($i = 0; $i < $len; $i++) {
            if (self::CHAR_NUM == $type) {
                if (0 == $i) {
                    $secret .= chr(rand(49, 57));
                } else {
                    $secret .= chr(rand(48, 57));
                }
            } else if (self::CHAR_WORD == $type) {
                $secret .= chr(rand(65, 90));
            } else {
                if (0 == $i) {
                    $secret .= chr(rand(65, 90));
                } else {
                    $secret .= ( 0 == rand(0, 1)) ? chr(rand(65, 90)) : chr(rand(48, 57));
                }
            }
        }
        return $secret;
    }

    /**
     * 生成可阅读随即字符串
     * @param <integer> $length
     * @return <string>
     */
    public static function readableRandomString($length = 12) {
        $conso = array("b", "c", "d", "f", "g", "h", "j", "k", "l",
            "m", "n", "p", "r", "s", "t", "v", "w", "x", "y", "z");
        $vocal = array("a", "e", "i", "o", "u");
        $password = "";
        srand((double) microtime() * 1000000);
        $max = $length / 2;
        for ($i = 1; $i <= $max; $i++) {
            $password .= $conso[rand(0, 19)];
            $password .= $vocal[rand(0, 4)];
        }
        return $password;
    }

    /**
     * 生成不可阅读随机字符串
     * @param <integer> $length
     * @return <string>
     */
    public static function generateRandomString($length = 12) {
        $c = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
        srand((double) microtime() * 1000000);
        for ($i = 0; $i < $length; $i++) {
            $rand .= $c[rand() % strlen($c)];
        }
        return $rand;
    }

    // 判断浏览器类型
    public static function getBrowser() {
        $http_user_agent = $_SERVER["HTTP_USER_AGENT"];
        if (strpos($http_user_agent, "MSIE 9.0"))
            return "Internet Explorer 9.0";
        else if (strpos($http_user_agent, "MSIE 8.0"))
            return "Internet Explorer 8.0";
        else if (strpos($http_user_agent, "MSIE 7.0"))
            return "Internet Explorer 7.0";
        else if (strpos($http_user_agent, "MSIE 6.0"))
            return "Internet Explorer 6.0";
        else if (strpos($http_user_agent, "Firefox/3"))
            return "Firefox 3";
        else if (strpos($http_user_agent, "Firefox/2"))
            return "Firefox 2";
        else if (strpos($http_user_agent, "Chrome"))
            return "Google Chrome";
        else if (strpos($http_user_agent, "Safari"))
            return "Safari";
        else if (strpos($http_user_agent, "Opera"))
            return "Opera";
        else
            return $http_user_agent;
    }

    /**
     * 字符串转数组
     * @param string $string
     * @param string $delimiter
     * @return array
     */
    public static function string2array($string, $delimiter = ',') {
        return preg_split("/\s*{$delimiter}\s*/", trim($string), -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * 数组转字符串
     * @param array $array
     * @param string $delimiter
     * @return string
     */
    public static function array2string($array, $delimiter = ',') {
        return implode($delimiter, $array);
    }

    /**
     * 文件大小格式化
     * @param integer $bytes
     * @param string $format
     * @return string
     */
    public static function formatFileSize($bytes, $format = 'MB') {
        $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');

        $bytes = max($bytes, 0);
        $expo = floor(($bytes ? log($bytes) : 0) / log(1024));
        $expo = min($expo, count($units) - 1);

        $bytes /= pow(1024, $expo);

        return Yii::app()->numberFormatter->format($format, $bytes) . ' ' . $units[$expo];
    }

    /**
     * 移除重复的字符
     * @param string $string
     * @return string
     */
    public static function removeRepeatWord($string) {
        return (!empty($string)) ? self::array2string(array_unique(self::string2array($string))) : $string;
    }

    /**
     * 数字 IP 地址转换为归属地
     * @param string $ip
     * @param string $charset
     * @return string
     */
    public static function ip2String($ip, $charset = 'utf-8') {
        Yii::import('ext.IpFinder.IpFinder');
        return IpFinder::convertIp($ip, $charset);
    }

    public static function funcGetArgString($args) {
        $return = '';
        if (is_array($args)) {
            foreach ($args as $arg) {
                $return .= $arg;
            }
        }

        return $return;
    }

    /**
     * 判断是否为 IE6
     * @return boolean
     */
    public static function isIE6() {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6') !== false;
    }

    /**
     * 身份证 15 位转 18 位长度
     * @param string $idCardNumber
     * @return string
     */
    public static function idCardNumber15218($idCardNumber) {
        // 取前17位验证
        $chackCard = substr($idCardNumber, 0, 17);
        // 加权因子
        $wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
        // 校验码串
        $ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
        $sigma = '';
        for ($i = 0; $i < 17; $i++) {
            // 提取前17位的其中一位，并将变量类型转为实数
            $b = (int) $chackCard{$i};
            // 提取相应的加权因子
            $w = $wi[$i];
            // 把从身份证号码中提取的一位数字和加权因子相乘，并累加
            $sigma += $b * $w;
        }

        // 计算序号
        $number = $sigma % 11;
        // 按照序号从校验码串中提取相应的字符。
        $check_number = $ai[$number];
        // 组合号码
        return $chackCard . $check_number;
    }

    /**
     * 根据省份证获取性别
     * @param string $idCardNumber
     * @return mixed
     */
    public static function getSexByIdCardNumber($idCardNumber) {
        $gather = self::idCardNumber15218($idCardNumber);
        if ($idCardNumber != $gather) {
            return null;
        } else {
            return ((substr($gather, 16, 1) % 2) == 0) ? Option::SEX_FEMALE : Option::SEX_MALE;
        }
    }

    /**
     * 根据省份证获取出生年月
     * @param string $idCardNumber
     * @return mixed
     */
    public static function getBirthdayByIdCardNumber($idCardNumber) {
        $gather = self::idCardNumber15218($idCardNumber);
        if ($idCardNumber != $gather) {
            return null;
        } else {
            return substr($gather, 6, 4) . '-' . substr($gather, 10, 2) . '-' . substr($gather, 12, 2);
        }
    }

    /**
     * 根据生日获取当前年龄
     * @param date $birthday
     * @return int|null
     */
    public static function getAges($birthday) {
        if (date('Y-m-d', strtotime($birthday)) == $birthday) {
            $birthday = strtotime($birthday);
            $ages = date('Y') - date('Y', $birthday);
            $months = date('m') - date('m', $birthday);
            if ($months > 0) {
                $ages += 1;
            } else if ($months == 0) {
                if (date('d') >= date('d', $birthday)) {
                    $ages += 1;
                }
            }

            return $ages;
        } else {
            return null;
        }
    }

}
