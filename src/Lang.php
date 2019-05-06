<?php
namespace qpf\lang;

/**
 * 多语言
 */
class Lang
{
    /**
     * 翻译者
     * @var Translator
     */
    protected $translator;
    
    /**
     * 首选语言包
     * @var string
     */
    protected $pack = 'zh-cn';
    /**
     * GET语言设定变量名
     * @var string
     */
    protected $langGetVar = 'use_lang';
    /**
     * Cookie语言设定变量名
     * @var string
     */
    protected $langCookieVar = 'for_lang';
    /**
     * 自动使用客户端语言类型
     * @var bool
     */
    protected $useClientLang = true;
    /**
     * 客户端可用的语言类型列表
     * @var array
     */
    protected $clientLangAllow = [
        'zh-cn', 'en-us'
    ];
    /**
     * Accept-Language语言类型映射列表
     * @var array
     */
    protected $acceptLanguage = [
        'zh-hans-cn'    => 'zh-cn',
    ];
    
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }
    
    public function set($name, $type)
    {
        $pack = $this->translator->getLangpack($type);
        // TODO 向语言包临时动态添加
    }
    
    /**
     * 获取翻译者
     * @return \qpf\lang\Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }
    
    /**
     * 设置翻译者
     * @param Translator $translator
     * @return \qpf\lang\Lang
     */
    public function setTranslator(Translator $translator)
    {
        $this->translator = $translator;
        
        return $this;
    }
    
    /**
     * 获取内部字符编码
     * @return mixed
     */
    public function getEncoding()
    {
        return mb_internal_encoding();
    }
    
    /**
     * 设置内部字符编码
     * @param string $encodeing 字符编码名称
     * @return $this
     */
    public function setEncoding($encoding)
    {
        ini_set('default_charset', $encoding);
        mb_internal_encoding($encoding);
        
        return $this;
    }
    
    /**
     * 获取时区
     * @return string
     */
    public function getTimeZone()
    {
        return date_default_timezone_get();
    }
    
    /**
     * 设置时区
     * @param string $timezone
     * @return $this
     */
    public function setTimeZone($timezone)
    {
        date_default_timezone_set($timezone);
        
        return $this;
    }
    
    /**
     * 翻译消息
     * @param string $type 类别
     * @param string $message 消息
     * @param array $params 参数
     * @param string $lang 目标语言
     * @return string
     */
    public function translate($type, $message, array $params = [], $lang = null)
    {
        $lang = $lang ?: $this->pack;
        
        return $this->translator->translate($type, $message, $params, $lang);
    }
    

    public function addLangPack($type, $langPack)
    {
        $this->translator->langpacks[$type] = $langPack;
    }
    
    /**
     * 格式化消息
     * @param string $message 消息
     * @param array $params 参数
     * @return string
     */
    public function format($message, array $params = [])
    {
        return $this->translator->format($message, $params);
    }
    
    /**
     * 获取货币符号
     * @param unknown $code
     */
    public function getCurrencySymbol($code)
    {
        if (!extension_loaded('intl')) {
            throw new \Exception('Need to install PHP intl extension');
        }
        
        $format = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        return $format->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }
    
    /**
     * 自动匹配客户端语言环境
     */
    public function auto()
    {
        // 不使用客户端语言
        if (!$this->useClientLang) {
            return $this->pack;
        }
        
        $pack = '';
        
        if (isset($_GET[$this->langGetVar])) {
            $pack = strtolower($_GET[$this->langGetVar]);
        } elseif (isset($_COOKIE[$this->langCookieVar])) {
            $pack = strtolower($_COOKIE[$this->langCookieVar]);
        } elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            preg_match('/^([a-z\d\-]+)/i', $_SERVER['HTTP_ACCEPT_LANGUAGE'], $matches);
            
            if (isset($matches[1])) {
                $pack = strtolower($matches[1]);
                if (isset($this->acceptLanguage[strtolower($matches[1])])) {
                    $pack = $this->acceptLanguage[$pack];
                }
            }
        }
        
        // 客户端允许语言过滤
        if (empty($this->clientLangAllow) || in_array($pack, $this->clientLangAllow)) {
            $this->pack = $pack ?: $this->pack;
        }
        
        return $this->pack;
    }
    
    /**
     * 设置客户端语言头部映射的语言包
     * @param array $accept 语言映射
     * @return void
     */
    public function setAcceptLanguage(array $accept)
    {
        $this->acceptLanguage = array_merge($this->acceptLanguage, $accept);
    }
    
    /**
     * 设置是否使用客户端语言
     * @param bool $use
     * @return void
     */
    public function useUseClientLang($use)
    {
        $this->useClientLang = $use;
    }
    
    /**
     * 是否自动识别客户端语言
     * @return bool
     */
    public function isAutoLang()
    {
        return $this->useClientLang;
    }
    
    /**
     * 设置客户端允许语言类型
     * @param array $allow
     * @return void
     */
    public function setClientAllowLang(array $allow)
    {
        $this->clientLangAllow = array_merge($this->clientLangAllow, $allow);
    }
    
    /**
     * 设置语言类型到Cookie
     * @param string $pack 语言包名
     * @return void
     */
    public function setCookieLang($pack = null)
    {
        $pack = $pack ?: $this->pack;
        
        $_COOKIE[$this->langCookieVar] = $pack;
    }
    
    /**
     * 设置Cookie保存变量名
     * @param string $var 变量名
     * @return void
     */
    public function setCookieVar($var)
    {
        $this->langCookieVar = $var;
    }
}