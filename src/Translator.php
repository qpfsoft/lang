<?php
namespace qpf\lang;

/**
 * 翻译者
 * 
 * 该类是语言包采集器
 */
class Translator
{
    /**
     * 语言包集合
     * ```
     * [
     *      '类别'    => '包对象'
     * ]
     * ```
     * @var array
     */
    public $langpacks;
    
    public function __construct(array $langpacks = [])
    {
        $this->langpacks = $langpacks;
    }
    
    /**
     * 翻译
     * @param string $type
     * @param string $message
     * @param array $params
     * @param string $lang
     */
    public function translate($type, $message, array $params, $lang)
    {
        $pack = $this->getLangpack($type);
        $message = $pack->translate($type, $message, $lang);
        return $this->format($message, $params);
    }
    
    /**
     * 加载语言包
     * @param string $type 类别
     * @throws \Exception
     * @return LangPack
     */
    public function getLangpack($type)
    {
        if (isset($this->langpacks[$type])) {
            $pack = $this->langpacks[$type];
            if ($pack instanceof LangPack) {
                return $pack;
            }
            
            return $this->langpacks[$type] = new $pack();
        }
        
        throw new \Exception('lang pack miss : ' . $type);
    }
    
   /**
    * 格式化消息
    * @param string $pattern 包含`{:name}` 或 `%s %d`字符串
    * @param array $args 替换值
    * @return string
    */
    public static function format($pattern, array $args)
    {
        if(!empty($args)) {
            if (key($args) === 0) {
                array_unshift($args, $pattern);
                $pattern = call_user_func_array('sprintf', $args);
            } else {
                $words = array_keys($args);
                foreach ($words as &$w) {
                    $w = "{:{$w}}";
                }
                $pattern = str_replace($words, $args, $pattern);
            }
        }
        
        return $pattern;
    }
}