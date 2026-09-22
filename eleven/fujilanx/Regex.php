<?php
/**
 * Created by PhpStorm.
 * Note: 正则表达工具类
 * User: chenkangfu
 * Date: 2018/9/17
 * Time: 22:50
 */

namespace fuji;

/**
 * Class Regex
 * @package fuji
 */
class Regex
{
    /**
     * @var array 预定义正则规则
     */
    private $validate = array(
        'require'       =>  '/.+/',                     //非空
        'email'         =>  '',                         //邮箱
        'url'           =>  '',                         //URL地址
        'mobile'        =>  '/^1(3|4|5|7|8)\d{9}/',     //手机
    );

    /**
     * @var bool false -> 返回是否匹配;true -> 返回匹配的结果数组
     */
    private $returnMatchResult = false;

    /**
     * @var null 修正符
     */
    private $fixMode = null;

    /**
     * @var array 匹配的结果数组
     */
    private $matches = array();

    /**
     * @var bool 匹配结果
     */
    private $isMatch = false;

    /**
     * Regex constructor.
     * @param bool|false $returnMatchResult
     * @param null $fixMode
     */
    public function __construct($returnMatchResult = false, $fixMode = null)
    {
        $this->returnMatchResult = $returnMatchResult;
        $this->fixMode = $fixMode;
    }

    /**
     * Note: 匹配函数
     * User: Eleven
     * Date: 2018/9/17 23:02
     * @param $pattern
     * @param $subject
     * @return array|bool
     */
    private function regex($pattern, $subject)
    {
        //检查是否在已定义的正则规则里面，否则用自定义的
        if(array_key_exists(strtolower($pattern),$this->validate))
        {
            //拼上修正符
            $pattern = $this->validate[$pattern].$this->fixMode;
        }

        //匹配结果
        $this->returnMatchResult ?
            preg_match_all($pattern, $subject, $this->matches) :
            $this->isMatch = preg_match($pattern, $subject) === 1;

        return $this->getRegexResult();
    }

    /**
     * Note: 返回匹配结果
     * User: Eleven
     * Date: 2018/9/17 23:10
     * @return array|bool
     */
    private function getRegexResult()
    {
        if($this->returnMatchResult){
            return $this->matches;
        }else{
            return$this->isMatch;
        }
    }

    /**
     * Note: 切换返回类型
     * User: Eleven
     * Date: 2018/9/17 23:12
     * @param null $bool
     */
    public function returnType($bool = null)
    {
        if(empty($bool)){
            $this->returnMatchResult = !$this->returnMatchResult;
        }else{
            $this->returnMatchResult = is_bool($bool) ? $bool : (bool)$bool;
        }
    }

    /**
     * Note: 修改修正符
     * User: Eleven
     * Date: 2018/9/17 23:16
     * @param $fixMode
     */
    public function setFixMode($fixMode)
    {
        $this->fixMode = $fixMode;
    }

    /**
     * Note: 非空验证
     * User: Eleven
     * Date: 2018/9/17 23:17
     * @param $subject
     * @return array|bool
     * @internal param $str
     */
    public function noEmpty($subject)
    {
        return $this->regex('require',$subject);
    }

    /**
     * Note: 邮箱验证
     * User: Eleven
     * Date: 2018/9/17 23:21
     * @param $email
     * @return array|bool
     */
    public function isEmail($email)
    {
        return $this->regex('email',$email);
    }

    /**
     * Note: 手机号验证
     * User: Eleven
     * Date: 2018/9/17 23:22
     * @param $mobile
     * @return array|bool
     */
    public function isMobile($mobile)
    {
        return $this->regex('mobile',$mobile);
    }

    /**
     * Note: 自定义正则规则
     * User: Eleven
     * Date: 2018/9/17 23:24
     * @param $pattern  正则规则
     * @param $subject  匹配的字符串
     * @return array|bool
     */
    public function check($pattern, $subject)
    {
        return $this->regex($pattern,$subject);
    }
}