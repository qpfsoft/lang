<?php
namespace qpf\lang;

/**
 * 语言信息格式化
 */
class LangFormat
{

    /**
     * 格式化消息
     * @param string $pattern 包含`{name}`字符串
     * @param array $args 标签值
     * @return string
     */
    public function format($pattern, array $args)
    {
        if (false === ($parse = self::parseBracket($pattern))) {
            return false;
        }

        foreach ($parse as $index => $part) {
            if (is_array($part)) {
                $parse[$index] = $this->replaceBracket($part, $args);
            }
        }
        var_dump($parse);
        return join('', $parse);
    }
    
    /**
     * 解析花括号
     * @param string $pattern 包含`{name}`字符串
     * @return array|false
     */
    public static function parseBracket($pattern)
    {
        $charset = mb_internal_encoding();
        $depth = 1;
        if (($start = $pos = mb_strpos($pattern, '{', 0, $charset)) === false) {
            return [$pattern];
        }
        $tokens = [mb_substr($pattern, 0, $pos, $charset)];
        while (true) {
            $open = mb_strpos($pattern, '{', $pos + 1, $charset);
            $close = mb_strpos($pattern, '}', $pos + 1, $charset);
            if ($open === false && $close === false) {
                break;
            }
            if ($open === false) {
                $open = mb_strlen($pattern, $charset);
            }
            if ($close > $open) {
                $depth++;
                $pos = $open;
            } else {
                $depth--;
                $pos = $close;
            }
            if ($depth === 0) {
                $tokens[] = explode(',', mb_substr($pattern, $start + 1, $pos - $start - 1, $charset), 3);
                $start = $pos + 1;
                $tokens[] = mb_substr($pattern, $start, $open - $start, $charset);
                $start = $open;
            }
            
            if ($depth !== 0 && ($open === false || $close === false)) {
                break;
            }
        }
        if ($depth !== 0) {
            return false;
        }
        
        return $tokens;
    }
    
    /**
     * 替换花括号变量
     * ```
     * [
     *      0 => 'name',
     * ]
     * ```
     * @param array $bracket 
     * @param array $args
     * @return string
     */
    public function replaceBracket($bracket, array $args)
    {
        $mark = $bracket[0];
        if (isset($args[$mark])) {
            return $args[$mark];
        } else {
            return '{' . $mark . '}';
        }
    }
}