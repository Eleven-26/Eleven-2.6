<?php
/**
 * Created by PhpStorm.
 * Note: 模板引擎
 * User: chenkangfu
 * Date: 2018/9/18
 * Time: 0:05
 */

namespace fuji;

/**
 * Class Template
 * @package fuji
 */
class Template
{
    /**
     * @var 模板目录
     */
    private $templateDir;

    /**
     * @var 编译的文件目录
     */
    private $compileDir;

    /**
     * @var string 左边界符
     */
    private $leftTag = '{#';

    /**
     * @var string 右边界符
     */
    private $rightTag = '#}';

    /**
     * @var string 当前编译的文件名
     */
    private $currentTemp = '';

    /**
     * @var 当前编译的HTML代码
     */
    private $outputHtml;

    /**
     * @var array 变量池
     */
    private $varPool = array();

    /**
     * Template constructor.
     * @param $templateDir
     * @param $compileDir
     * @param null $leftTag
     * @param null $rightTag
     */
    public function __construct($templateDir, $compileDir, $leftTag = null, $rightTag = null)
    {
        $this->templateDir = $templateDir;
        $this->compileDir = $compileDir;
        if(!empty($leftTag))
        {
            $this->leftTag = $leftTag;
        }
        if(!empty($rightTag))
        {
            $this->rightTag = $rightTag;
        }
    }

    /**
     * Note: 输出变量到模板
     * User: Eleven
     * Date: 2018/9/18 0:19
     * @param $tag
     * @param $var
     */
    public function assign($tag, $var)
    {
        $this->varPool[$tag] = $var;
    }

    /**
     * Note: 获取变量的值
     * User: Eleven
     * Date: 2018/9/18 0:20
     * @param $tag
     * @return mixed
     */
    public function getVar($tag)
    {
        return $this->varPool[$tag];
    }

    /**
     * Note: 获取原文件
     * User: Eleven
     * Date: 2018/9/18 0:22
     * @param $templateName
     * @param string $ext
     */
    public function getSourceTemplate($templateName, $ext = '.html')
    {
        $this->currentTemp = $templateName;
        $sourceFilename = $this->templateDir.$this->currentTemp.$ext;
        $this->outputHtml = file_get_contents($sourceFilename);
    }

    /**
     * Note: 编译文件
     * User: Eleven
     * Date: 2018/9/18 0:25
     * @param null $templateName
     * @param string $ext
     */
    public function compileTemplate($templateName = null, $ext = '.html')
    {
        $templateName = empty($templateName) ? $this->currentTemp : $templateName;

        //开始编译，正则替换，如：{# $test #}
        //preg_quote，对字符串进行转译
        $pattern = '/' . preg_quote($this->leftTag);
        $pattern .= ' *\$([a-zA-Z_]\w*) *';
        $pattern .= preg_quote($this->rightTag) . '/';

        //匹配替换
        $this->outputHtml = preg_replace($pattern, '<? echo $this->getVar(\'$1\') ?>', $this->outputHtml);

        //目标文件路径
        $compileFilename = $this->compileDir.md5($templateName).$ext;

        //生成文件
        file_put_contents($compileFilename,$this->outputHtml);
    }

    /**
     * Note: 获取编译后的模板文件
     * User: Eleven
     * Date: 2018/9/18 0:58
     * @param null $templateName
     * @param string $ext
     */
    public function display($templateName = null, $ext = '.html')
    {
        $templateName = empty($templateName) ? $this->currentTemp : $templateName;
        include_once $compileFilename = $this->compileDir.md5($templateName).$ext;
    }

}