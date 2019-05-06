<?php
namespace qpf\lang;

/**
 * 词典语言包
 */
class DictionaryLangPack extends ArrayLangPack
{
    /**
     * 是否基于标记的语言包
     * @var string
     */
    protected $isMark = false;

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
        
        // 翻译短语
        $message = $this->parsePhrase($message, $dicts);
        
        // 无空格直接返回
        if (ctype_graph($message)) {
            return $message;
        }
        
        // 翻译单词
        $message = explode(' ', $message);
        foreach ($message as $key => $val) {
            $message[$key] = $this->parseWord($val, $dicts);
        }
        
        return implode(' ', $message);
    }
    
    /**
     * 解析单词
     *
     * @param string $str 一个单词
     * @param array $dicts 词典
     * @return string 解析成功返回解释，否则原样返回
     */
    protected function parseWord($str, array $dicts)
    {
        if (empty($str)) {
            return '';
        }
        
        // 符号库
        $symbol = [':', ',', '!', ';'];
        
        if (strlen($str) === 1) {
            $last_symbol = null;
            $first_symbol = $str;
        } else {
            // 获取字符最后一位
            $last_symbol = substr($str, - 1, 1);
            // 单词首字母
            $first_symbol = strtolower(substr($str, 0, 1));
        }
        
        // 允许单词尾部是附加的一个符号
        if ($last_symbol !== null && in_array($last_symbol, $symbol)) {
            $str = rtrim($str, $last_symbol);
            $str = self::parseWord($str, $dicts) . $last_symbol;
        }
        
        // 单词库
        if (isset($dicts['word'])) {
            // 支持首字母分组
            if (isset($dicts['word'][$first_symbol])) {
                $lib = $dicts['word'][$first_symbol];
            } else {
                $lib = $dicts['word'];
            }
        }
        
        if (isset($lib[strtolower($str)])) {
            return $lib[strtolower($str)];
        }
        
        return $str;
    }
    
    /**
     * 解析短语
     *
     * @param string $str 一句短语
     * @param array $dicts 词典
     * @return string 解析短语中部分内容
     */
    protected function parsePhrase($str, array $dicts)
    {
        /* 特殊短语，多个单词组成不可分割, 不区分大小写 */
        if (!isset($dicts['phrase'])) {
            return $str;
        }
        
        foreach ($dicts['phrase'] as $phrase => $parse) {
            if (false !== ($pos = stripos($str, $phrase))) {
                
                // bug: `be a` 会错误的替换 `be assigned` 为 `是一个ssigned`
                // 修复: 判断要替换值紧挨的下一个字符为空或空格
                $next = substr($str, $pos + strlen($phrase), 1);
                if ($next === '' || $next === ' ') {
                    $str = str_ireplace($phrase, $parse, $str);
                }
            }
        }
        
        
        
        return $str;
    }
}