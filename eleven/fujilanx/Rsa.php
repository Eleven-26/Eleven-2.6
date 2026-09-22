<?php
/**
 * PHP RSA 非对称 加密解密、签名验签的实现
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-4-28
 * Time: 11:32
 */

namespace fuji;


class Rsa
{
    private $public_key_resource = ''; //公钥资源
    private $private_key_resource = ''; //私钥资源

    /**
     * Rsa constructor.
     * @param $public_key 公钥数据字符串
     * @param $private_key 私钥数据字符串
     */
    public function __construct($public_key,$private_key) {
        $this->public_key_resource = !empty($public_key) ? openssl_pkey_get_public($this->get_public_key($public_key)) : false;
        $this->private_key_resource = !empty($private_key) ? openssl_pkey_get_private($this->get_private_key($private_key)) : false;
    }

    /**
     * Note：获取私有key字符串 重新格式化  为保证任何key都可以识别
     * User：Eleven
     * Date：2019-4-28 11:34
     * @param $private_key
     * @return string
     */
    public function get_private_key($private_key){
        $search = [
            "-----BEGIN RSA PRIVATE KEY-----",
            "-----END RSA PRIVATE KEY-----",
            "\n",
            "\r",
            "\r\n"
        ];

        $private_key = str_replace($search,"",$private_key);
        return $search[0] . PHP_EOL . wordwrap($private_key, 64, "\n", true) . PHP_EOL . $search[1];
    }


    /**
     * Note：获取公共key字符串  重新格式化 为保证任何key都可以识别
     * User：Eleven
     * Date：2019-4-28 11:34
     * @param $public_key
     * @return string
     */
    public function get_public_key($public_key){
        $search = [
            "-----BEGIN PUBLIC KEY-----",
            "-----END PUBLIC KEY-----",
            "\n",
            "\r",
            "\r\n"
        ];
        $public_key = str_replace($search,"",$public_key);
        return $search[0] . PHP_EOL . wordwrap($public_key, 64, "\n", true) . PHP_EOL . $search[1];
    }

    /**
     * Note：生成一对公私钥 成功返回 公私钥数组 失败 返回 false
     * User：Eleven
     * Date：2019-4-28 11:35
     * @return array|bool
     */
    public function create_key() {
        $res = openssl_pkey_new();
        if($res == false) return false;
        openssl_pkey_export($res, $private_key);
        $public_key = openssl_pkey_get_details($res);
        return array('public_key' => $public_key["key"],'private_key' => $private_key);
    }

    /**
     * Note：用私钥加密
     * User：Eleven
     * Date：2019-4-28 11:35
     * @param $input
     * @return string
     */
    public function private_encrypt($input) {
        openssl_private_encrypt($input,$output,$this->private_key_resource);
        return base64_encode($output);
    }

    /**
     * Note：用公钥解密私钥加密后的密文
     * User：Eleven
     * Date：2019-4-28 14:52
     * @param $input
     * @return mixed
     */
    public function public_decrypt($input) {
        openssl_public_decrypt(base64_decode($input),$output,$this->public_key_resource);
        return $output;
    }

    /**
     * Note：用公钥加密
     * User：Eleven
     * Date：2019-4-28 11:36
     * @param $input
     * @return string
     */
    public function public_encrypt($input) {
        openssl_public_encrypt($input,$output,$this->public_key_resource,OPENSSL_PKCS1_OAEP_PADDING);
        return base64_encode($output);
    }

    /**
     * Note：用私钥解密公钥加密后的密文
     * User：Eleven
     * Date：2019-4-28 14:52
     * @param $input
     * @return mixed
     */
    public function private_decrypt($input) {
        openssl_private_decrypt(base64_decode($input),$output,$this->private_key_resource,OPENSSL_PKCS1_OAEP_PADDING);
        return $output;
    }

    /**
     * Note：公钥加密（由于1024 bit的钥匙加密的最大长度为117字节，所以将待加密数据按117分割，分断加密之后，连接返加）
     * User：Eleven
     * Date：2019-4-28 14:51
     * @param $data 待加密数据
     * @param $publicKey 公钥
     * @return string 返回加密后的字符串
     */
    public function encryptPublicKey($data,$publicKey){
        $encrypt = '';
        foreach (str_split($data,117) as $item){
            $temp = '';
            openssl_public_encrypt($item,$encrypt,$publicKey);
            $encrypt .= $temp;
        }
        openssl_free_key($publicKey);
        return base64_encode($encrypt);
    }

    /**
     * Note：私钥解密（待解密字符串长度超过128之后， 按128切割之后分断解密）
     * User：Eleven
     * Date：2019-4-28 14:51
     * @param $data 密钥串
     * @param $privateKey 私钥
     * @return string 解密之后的字符串
     */
    public function decryptPrivateKey($data,$privateKey){
        $data = base64_decode($data);
        $decrypt = '';
        foreach (str_split($data,128) as $item){
            $temp = '';
            openssl_private_decrypt($item,$temp,$privateKey);
            $decrypt .= $temp;
        }
        openssl_free_key($privateKey);
        return $decrypt;
    }

    /**
     * Note：RSA 签名
     * User：Eleven
     * Date：2019-4-28 14:29
     * @param $data 待签名数据
     * @param $private_key 私钥字符串
     * @return string
     */
    public function rsaSign($data, $private_key) {

        $search = [
            "-----BEGIN RSA PRIVATE KEY-----",
            "-----END RSA PRIVATE KEY-----",
            "\n",
            "\r",
            "\r\n"
        ];

        $private_key = str_replace($search,"",$private_key);
        $private_key = $search[0] . PHP_EOL . wordwrap($private_key, 64, "\n", true) . PHP_EOL . $search[1];
        $res = openssl_get_privatekey($private_key);

        if($res)
        {
            openssl_sign($data, $sign, $res);
            openssl_free_key($res);
        }else {
            exit("私钥格式有误");
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * Note：RSA 验签
     * User：Eleven
     * Date：2019-4-28 14:27
     * @param $data 待签名数据
     * @param $public_key 公钥字符串
     * @param $sign 要校对的的签名结果
     * @return bool
     */
    public function rsaCheck($data, $public_key, $sign)  {
        $search = [
            "-----BEGIN PUBLIC KEY-----",
            "-----END PUBLIC KEY-----",
            "\n",
            "\r",
            "\r\n"
        ];
        $public_key = str_replace($search,"",$public_key);
        $public_key = $search[0] . PHP_EOL . wordwrap($public_key, 64, "\n", true) . PHP_EOL . $search[1];
        $res = openssl_get_publickey($public_key);
        if($res)
        {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
            openssl_free_key($res);
        }else{
            exit("公钥格式有误!");
        }
        return $result;
    }

    /**
     * Note：如果公钥和私钥是以字符串的形式提供的， 那么需要将公私钥组成相应的格式（每行64个字符串）
     * User：Eleven
     * Date：2019-4-28 14:47
     * @param $data $data为待加密字符串
     * @param $public_key 为公钥字符串
     * @return string
     */
    public function encode_pay($data, $public_key)
    {
        $pay_public_key = "-----BEGIN PUBLIC KEY-----\r\n";
        foreach (str_split($public_key, 64) as $str) {
            $pay_public_key = $pay_public_key . $str . "\r\n";
        }
        $pay_public_key = $pay_public_key . "-----END PUBLIC KEY-----";
        $pu_key = openssl_pkey_get_public($pay_public_key);
        if ($pu_key == false) {
            echo "打开公钥出错";
            die;
        }
        $encryptData = '';
        $crypt = '';
        foreach (str_split($data, 117) as $chunk) {
            openssl_public_encrypt($chunk, $encryptData, $pu_key);
            $crypto = $crypt . $encryptData;
        }
        $crypt = base64_encode($crypto);
        return $crypt;
    }

}
