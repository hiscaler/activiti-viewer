<?php

class StringHelper {

    // 全角转半角
    public static function makeSemiangle($str) {
        $arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4',
            '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E',
            'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O',
            'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y',
            'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i',
            'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's',
            'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z',
            '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[',
            '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']',
            '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<',
            '》' => '>',
            '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-',
            '：' => ':', '。' => '.', '、' => ',', '，' => ',', '、' => '.',
            '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|',
            '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"',
            '　' => ' ', '＄' => '$', '＠' => '@', '＃' => '#', '＾' => '^', '＆' => '&', '＊' => '*',
            '＂' => '"');

        return strtr($str, $arr);
    }

    // 计算字符串长度（汉字按两字符算）
    public static function str_len($str) {
        $length = strlen(preg_replace('/[\x00-\x7F]/', '', $str));
        if ($length) {
            return strlen($str) - $length + intval($length / 3);
        } else {
            return strlen($str);
        }
    }

    // PHPCMS 加解密函数
    public static function phpcms_encode($txt, $key) {
        srand((double) microtime() * 1000000);
        $encrypt_key = md5(rand(0, 32000));
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $encrypt_key[$ctr] . ($txt[$i] ^ $encrypt_key[$ctr++]);
        }
        return base64_encode(phpcms_key($tmp, $key));
    }

    public static function phpcms_decode($txt, $key) {
        $txt = phpcms_key(base64_decode($txt), $key);
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $md5 = $txt[$i];
            $tmp .= $txt[++$i] ^ $md5;
        }
        return $tmp;
    }

    public function phpcms_key($txt, $encrypt_key) {
        $encrypt_key = md5($encrypt_key);
        $ctr = 0;
        $tmp = '';
        for ($i = 0; $i < strlen($txt); $i++) {
            $ctr = $ctr == strlen($encrypt_key) ? 0 : $ctr;
            $tmp .= $txt[$i] ^ $encrypt_key[$ctr++];
        }
        return $tmp;
    }

    // 正则高亮关键字
    function highlight_words($str, $words, $color = '#FFFF00') {
        if (is_array($words)) {
            foreach ($words as $k => $word) {
                $pattern[$k] = "/\b($word)\b/is";
                $replace[$k] = '<font style="background-color:' . $color . ';">\\1</font>';
            }
        } else {
            $pattern = "/\b($words)\b/is";
            $replace = '<font style="background-color:' . $color . ';">\\1</font>';
        }

        return preg_replace($pattern, $replace, $str);
    }

    public static function html2Text($str, $r = false) {
        return $r ? addslashes(self::_html2Text(stripslashes($str))) : self::_html2Text($str);
    }

    private static function _html2Text($str) {
        $str = preg_replace("/<sty(.*)\\/style>|<scr(.*)\\/script>|<!--(.*)-->/isU", "", $str);
        $alltext = "";
        $start = 1;
        for ($i = 0; $i < strlen($str); $i++) {
            if ($start == 0 && $str[$i] == ">") {
                $start = 1;
            } else if ($start == 1) {
                if ($str[$i] == "<") {
                    $start = 0;
                    $alltext .= " ";
                } else if (ord($str[$i]) > 31) {
                    $alltext .= $str[$i];
                }
            }
        }
        $alltext = str_replace("　", " ", $alltext);
        $alltext = preg_replace("/&([^;&]*)(;|&)/", "", $alltext);
        $alltext = preg_replace("/[ ]+/s", "", $alltext);
        return $alltext;
    }

//    public static function str_len($string, $encoding = 'utf-8') {
////        if ($this->encoding !== false && function_exists('mb_strlen')) {
////			return mb_strlen($string, $encoding);
////        } else {
////            return strlen($string);
////        }
//    }
//
//
    /**
     * 检测字符串是否为 UTF-8 编码
     * @param string $str
     * @return boolean
     */
    public static function isUtf8($str) {
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c >= 254))
                    return false;
                elseif ($c >= 252)
                    $bits = 6;
                elseif ($c >= 248)
                    $bits = 5;
                elseif ($c >= 240)
                    $bits = 4;
                elseif ($c >= 224)
                    $bits = 3;
                elseif ($c >= 192)
                    $bits = 2;
                else
                    return false;
                if (($i + $bits) > $len)
                    return false;
                while ($bits > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191)
                        return false;
                    $bits--;
                }
            }
        }
        return true;
    }

    //裁剪字符串，加“...”
    static function substr($str, $length, $endfix = '...') {
        mb_internal_encoding("UTF-8");
        $str_length = mb_strwidth($str);
        if ($str_length > $length * 2) {
            return mb_substr($str, 0, $length) . $endfix;
        } else {
            return $str;
        }
    }

    //裁剪字符串，不加“...”
    static function cutstr($str, $startstr, $endstr) {
        $length = strlen($str);
        $start = mb_strpos($str, $startstr);
        $str = substr($str, $start, $length - $start);

        $end = mb_strpos($str, $endstr);
        return mb_substr($str, 0, $end);
    }

    //自动探测字符编码，并转换到指定编码

    static function convertEncoding($data, $to) {
        $encode_arr = array('UTF-8', 'GBK', 'GB2312', 'BIG5', 'CP936');
        if (get_extension_funcs('mbstring')) {
            $encoded = mb_detect_encoding($data, $encode_arr);
            $data = mb_convert_encoding($data, $to, $encoded);
        }
        return $data;
    }

    /**
      +----------------------------------------------------------
     * 检查字符串是否是UTF8编码
      +----------------------------------------------------------
     * @param string $string 字符串
      +----------------------------------------------------------
     * @return Boolean
      +----------------------------------------------------------
     */
    public static function is_utf8($str) {
        $c = 0;
        $b = 0;
        $bits = 0;
        $len = strlen($str);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($str[$i]);
            if ($c > 128) {
                if (($c >= 254))
                    return false;
                elseif ($c >= 252)
                    $bits = 6;
                elseif ($c >= 248)
                    $bits = 5;
                elseif ($c >= 240)
                    $bits = 4;
                elseif ($c >= 224)
                    $bits = 3;
                elseif ($c >= 192)
                    $bits = 2;
                else
                    return false;
                if (($i + $bits) > $len)
                    return false;
                while ($bits > 1) {
                    $i++;
                    $b = ord($str[$i]);
                    if ($b < 128 || $b > 191)
                        return false;
                    $bits--;
                }
            }
        }
        return true;
    }

    /**
      +----------------------------------------------------------
     * 字符串截取，支持中文和其他编码
      +----------------------------------------------------------
     * @static
     * @access public
      +----------------------------------------------------------
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    static public function msubstr($str, $start, $length, $charset = "utf-8", $suffix = '') {
        if (function_exists("mb_substr"))
            return mb_substr($str, $start, $length, $charset);
        elseif (function_exists('iconv_substr')) {
            return iconv_substr($str, $start, $length, $charset);
        }
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
        return (empty($suffix)) ? $slice : $slice . $suffix;
    }

    /**
      +----------------------------------------------------------
     * 产生随机字串，可用来自动生成密码
     * 默认长度6位 字母和数字混合 支持中文
      +----------------------------------------------------------
     * @param string $len 长度
     * @param string $type 字串类型
     * 0 字母 1 数字 其它 混合
     * @param string $addChars 额外字符
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    public static function randString($len = 6, $type = '', $addChars = '') {
        $str = '';
        switch ($type) {
            case 0:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 1:
                $chars = str_repeat('0123456789', 3);
                break;
            case 2:
                $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' . $addChars;
                break;
            case 3:
                $chars = 'abcdefghijklmnopqrstuvwxyz' . $addChars;
                break;
            case 4:
                $chars = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借" . $addChars;
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars = 'ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789' . $addChars;
                break;
        }
        if ($len > 10) {//位数过长重复字符串一定次数
            $chars = $type == 1 ? str_repeat($chars, $len) : str_repeat($chars, 5);
        }
        if ($type != 4) {
            $chars = str_shuffle($chars);
            $str = substr($chars, 0, $len);
        } else {
            // 中文随机字
            for ($i = 0; $i < $len; $i++) {
                $str.= self::msubstr($chars, floor(mt_rand(0, mb_strlen($chars, 'utf-8') - 1)), 1);
            }
        }
        return $str;
    }

    /**
      +----------------------------------------------------------
     * 生成一定数量的随机数，并且不重复
      +----------------------------------------------------------
     * @param integer $number 数量
     * @param string $len 长度
     * @param string $type 字串类型
     * 0 字母 1 数字 其它 混合
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    public static function build_count_rand($number, $length=4, $mode=1) {
        if ($mode == 1 && $length < strlen($number)) {
            //不足以生成一定数量的不重复数字
            return false;
        }
        $rand = array();
        for ($i = 0; $i < $number; $i++) {
            $rand[] = rand_string($length, $mode);
        }
        $unqiue = array_unique($rand);
        if (count($unqiue) == count($rand)) {
            return $rand;
        }
        $count = count($rand) - count($unqiue);
        for ($i = 0; $i < $count * 3; $i++) {
            $rand[] = rand_string($length, $mode);
        }
        $rand = array_slice(array_unique($rand), 0, $number);
        return $rand;
    }

    /**
      +----------------------------------------------------------
     *  带格式生成随机字符 支持批量生成
     *  但可能存在重复
      +----------------------------------------------------------
     * @param string $format 字符格式
     *     # 表示数字 * 表示字母和数字 $ 表示字母
     * @param integer $number 生成数量
      +----------------------------------------------------------
     * @return string | array
      +----------------------------------------------------------
     */
    public static function build_format_rand($format, $number=1) {
        $str = array();
        $length = strlen($format);
        for ($j = 0; $j < $number; $j++) {
            $strtemp = '';
            for ($i = 0; $i < $length; $i++) {
                $char = substr($format, $i, 1);
                switch ($char) {
                    case "*"://字母和数字混合
                        $strtemp .= String::rand_string(1);
                        break;
                    case "#"://数字
                        $strtemp .= String::rand_string(1, 1);
                        break;
                    case "$"://大写字母
                        $strtemp .= String::rand_string(1, 2);
                        break;
                    default://其他格式均不转换
                        $strtemp .= $char;
                        break;
                }
            }
            $str[] = $strtemp;
        }

        return $number == 1 ? $strtemp : $str;
    }

    /**
      +----------------------------------------------------------
     * 获取一定范围内的随机数字 位数不足补零
      +----------------------------------------------------------
     * @param integer $min 最小值
     * @param integer $max 最大值
      +----------------------------------------------------------
     * @return string
      +----------------------------------------------------------
     */
    public static function rand_number($min, $max) {
        return sprintf("%0" . strlen($max) . "d", mt_rand($min, $max));
    }

    /**
     * 剔除字符串中的空格（包括中文和英文空格）
     * @param string $string
     * @return string
     */
    public static function removeSpace($string) {
        $string = preg_replace("/[\s]{2,}/", "", $string);
        $string = str_replace('　', '', $string);
        return str_replace(' ', '', $string);
    }

    // 获取汉语拼音的第一个首字母，默认返回大写
    public static function getFirstWordPinYin($string, $to_upper = true) {
        if (empty($string))
            return '';
        Yii::import('ext.HanZi.HanZiTools');
        $hz = new HanZiTools();
        $pinyin = '';
        $stringArray = explode(' ', $hz->pzhanzi_hanzi_to_pinyin($string));
        if (is_array($stringArray)) {
            foreach ($stringArray as $value) {
                $pinyin .= substr($value, 0, 1);
            }
        }
        $pinyin = substr($pinyin, 0, 1);

        return ($to_upper) ? strtoupper($pinyin) : strtolower($pinyin);
    }

    /**
     * 获取汉字拼音
     * @param string $string
     * @param boolean $ucFirst
     * @param boolean $polyphony
     * @param string $separator
     * @return string
     */
    public static function getPinYin($string, $ucFirst = true, $polyphony = true, $separator = '-') {
        if (empty($string)) {
            return '';
        }
        Yii::import('ext.pinYin.PinYin');
        $py = new PinYin();
        return $py->encode(trim(self::makeSemiangle($string)), $ucFirst, $polyphony, $separator);
    }

    public static function truncateText($text, $length = 30, $truncate_string = '...', $truncate_lastspace = false) {
        if ($text == '') {
            return '';
        }

        $mbstring = extension_loaded('mbstring');
        if ($mbstring) {
            $old_encoding = mb_internal_encoding();
            @mb_internal_encoding(mb_detect_encoding($text));
        }
        $strlen = ($mbstring) ? 'mb_strlen' : 'strlen';
        $substr = ($mbstring) ? 'mb_substr' : 'substr';

        if ($strlen($text) > $length) {
            $truncate_text = $substr($text, 0, $length - $strlen($truncate_string));
            if ($truncate_lastspace) {
                $truncate_text = preg_replace('/\s+?(\S+)?$/', '', $truncate_text);
            }
            $text = $truncate_text . $truncate_string;
        }

//        if ($mbstring) {
//            @mb_internal_encoding($old_encoding);
//        }

        return $text;
    }

    public static function format($content, $formatType = 'markdown') {
        if (empty($content)) {
            return $content;
        }
        switch (strtolower($formatType)) {
            case 'markdown':
                $parser = new CMarkdownParser();
                return $parser->safeTransform($content);
                break;
            default:
                return $content;
        }
    }

}