<?php
/**
 * PHP AES 对称 加解密实现
 * Created by PhpStorm.
 * User: Eleven
 * Date: 2019-4-28
 * Time: 16:26
 */

namespace fuji;


class Aes
{
    /**
     * var string $method 加解密方法，可通过openssl_get_cipher_methods()获得
     */
    protected $method;

    /**
     * var string $secret_key 加解密的密钥
     */
    protected $secret_key;

    /**
     * var string $iv 加解密的向量，有些方法需要设置比如CBC
     */
    protected $iv;

    /**
     * var string $options （不知道怎么解释，目前设置为0没什么问题）
     */
    protected $options;

    /**
     * Aes constructor.
     * @param $key 密钥
     * @param string $method 加密方式
     * @param string $iv iv向量
     * @param int $options
     */
    public function __construct($key, $method = 'AES-128-ECB', $iv = '', $options = 0)
    {
        // key是必须要设置的
        $this->secret_key = isset($key) ? $key : 'morefun';

        $this->method = $method;

        $this->iv = $iv;

        $this->options = $options;
    }

    /**
     * Note：加密方法，对数据进行加密
     * User：Eleven
     * Date：2019-4-28 16:45
     * @param $data
     * @return string 返回加密后的数据
     */
    public function encrypt($data)
    {
        return openssl_encrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }

    /**
     * Note：解密方法，对数据进行解密
     * User：Eleven
     * Date：2019-4-28 16:46
     * @param $data
     * @return string 返回解密后的数据
     */
    public function decrypt($data)
    {
        return openssl_decrypt($data, $this->method, $this->secret_key, $this->options, $this->iv);
    }

}