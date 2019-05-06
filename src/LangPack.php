<?php
namespace qpf\lang;

/**
 * 语言包抽象类
 * 
 * ```
 * /root
 * |- lang
 * |    |- zh
 * |    |   |- simple
 * |    |   |- precision
 * ```
 * 
 * ```en
 * [
 *      'welcome' => 'Hello {name}, nice to meet you!',
 * ]
 * ```
 * 
 * ```zh
 * [
 *      'welcome' => '你好 {name}, 见到你很高兴!',
 * ]
 * ```
 * 
 * 
 * 基于词典
 * ```zh
 * [
 *      'hello' => '你好',
 * ]
 * ```
 */
abstract class LangPack
{
    /**
     * 语言映射
     * @var array
     */
    protected $langs = [];
    /**
     * 是否基于标记的语言包
     * @var string
     */
    protected $isMark = true;
    /**
     * 基础语言类型
     * @var string
     */
    protected $baseLang = 'en-us';
    
    /**
     * 加载语言包
     * @param string $type 类别
     * @param string $lang 语言
     * @return array
     */
    abstract protected function land($type, $lang);
    
    /**
     * 获取语言指定类型字典
     * @param string $type 类别
     * @param string $lang 语言
     */
    public function getDicts($type, $lang)
    {
        $pack = $lang . '/' . $type;
        if (!isset($this->langs[$pack])) {
            $this->langs[$pack] = $this->land($type, $lang);
        }
        
        return $this->langs[$pack];
    }
    
    /**
     * 翻译消息到指定类别语言
     * @param string $type 类别
     * @param string $message 消息
     * @param string $lang 语言
     */
    public function translate($type, $message, $lang)
    {
        if ($this->isMark || $lang !== $this->baseLang) {
            return $this->translateMessage($type, $message, $lang);
        }
        
        return null;
    }
    
    /**
     * 翻译指定消息
     * @param string $type 类别
     * @param string $message 消息
     * @param string $lang 语言
     * @return string|null
     */
    protected function translateMessage($type, $message, $lang)
    {
        // 加载语言包字典
        $dicts = $this->getDicts($type, $lang);
        if (isset($dicts[$message])) {
            return $dicts[$message];
        }
        
        return null;
    }
}