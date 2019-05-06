<?php
use qpf\deunit\TestUnit;

include __DIR__ . '/../boot.php';

/**
 * 语言环境
 *
 * PHP >= 5.3.0 内置该扩展
 * 
 * 提供函数或静态方法调用.
 */
class LocaleTest extends TestUnit
{
    public function setUp()
    {
        if (!extension_loaded('intl')) {
            exit('intl miss');
        }
    }
    
    /**
     * 尝试根据HTTP“Accept-Language”标头找出最佳可用语言环境
     * 
     * 如果用户浏览器不发送HTTP_ACCEPT_LANGUAGE, 将为null,
     * 所以请记住设置故障转移方案
     * 
     * @return string|false // false| zh
     */
    public function testTcceptFromHttp()
    {
        // cli 没有该头部信息
        
        $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        
        // 函数: locale_accept_from_http($header);
        
        //return \Locale::acceptFromHttp($header); // false
        
        return locale_accept_from_http($header);
    }
    
    /**
     * 规范化语言环境字符串
     */
    public function testCanonicalize()
    {
        return [
            'zh-CN' => locale_canonicalize('zh-CN'),
            'zh@CN' => locale_canonicalize('zh@CN'),
            'zh-CN.abc' => locale_canonicalize('zh-CN.abc'),
            'zh' => locale_canonicalize('zh'),
            '' => locale_canonicalize(''),
        ];
    }
    
    /**
     * 返回正确排序和分隔的区域设置ID
     */
    public function testCompose()
    {
        $subtags = [
            'language'=>'en' ,
            'script'  =>'Hans' ,
            'region'  =>'CN',
            'variant2'=>'rozaj' ,
            'variant1'=>'nedis' ,
            'private1'=>'prv1' ,
            'private2'=>'prv2'
        ];
        return locale_compose($subtags); // en_Hans_CN_nedis_rozaj_x_prv1_prv2
    }
    
    /**
     * 检查语言标记过滤器是否与区域设置匹配
     * @return string
     */
    public function testFilterMatches()
    {
        /**
         * (string $langtag ， string $locale [， bool $canonicalize=FALSE ])
         * $arg1 要检查的语言标记
         * $arg2 要检查的语言范围
         * $arg3 如果为true，则在匹配之前将参数转换为规范形式。
         * @return bool TRUE如果$ locale匹配$ langtag，FALSE否则。
         */
        
        
        return locale_filter_matches('de-DEVA', 'de-DE', false) ? '匹配' : '不匹配'; 
    }
    
    /**
     * 获取输入语言环境的变体
     * @return array
     */
    public function testGetAllVariants()
    {
        return locale_get_all_variants('sl_IT_NEDIS_ROJAZ_1901');
    }
    
    /**
     *  从INTL全局'default_locale'获取默认的语言环境值
     * @return string
     */
    public function testGetDefault()
    {
        return locale_get_default();
    }
    /**
     * 设置默认运行时区域设置
     * @param string $locale
     * @return string
     */
    public function testSetDefault()
    {
        locale_set_default('de-DE');
        
        return locale_get_default();
    }
    
    /**
     * 设置INTL全局'default_locale'获取默认的语言环境值
     * @return string
     */
    public function testinitSetDefault()
    {
        ini_set('intl.default_locale', 'de-DE');
        
        return locale_get_default();
    }
    
    /**
     * 获取 显示 语言
     * 返回inputlocale 语言的适当本地化显示名称
     * @param string $locale 用于返回显示名称的语言环境
     * @param string $in_locale 可选的格式区域设置
     * @return string 以适合$in_locale的格式显示语言环境的名称。
     */
    public function testGetDisplayLanguage()
    {
        return [
            'zh-hans-cn' => locale_get_display_language('zh-hans-cn'),
            'zh-hans-cn_zh-cn' => locale_get_display_language('zh-hans-cn', 'zh-cn'),
            'zh-hans-cn_zh-tw' => locale_get_display_language('zh-hans-cn', 'zh-tw'),
        ];
    }
    
    /**
     * 获取 显示 名称
     * 返回输入语言环境的相应本地化显示名称
     * @param string $locale 用于返回显示名称的语言环境
     * @param string $in_locale 可选的格式区域设置
     * @return string 以适合$in_locale的格式显示语言环境的名称。
     */
    public function testGetDisplayName()
    {
        return [
            'zh-hans-cn' => locale_get_display_name('zh-hans-cn'),
            'zh-hans-cn_zh-cn' => locale_get_display_name('zh-hans-cn', 'zh-cn'),
            'zh-hans-cn_zh-tw' => locale_get_display_name('zh-hans-cn', 'zh-tw'),
        ];
    }
    
    /**
     * 获取 显示 区域
     * 返回输入区域设置区域的适当本地化显示名称
     * @param string $locale 用于返回显示区域的区域设置
     * @param string $in_locale 用于显示区域名称的可选格式区域设置
     * @return string 以适合$in_locale的格式显示$locale的区域名称
     */
    public function testGetDisplayRegion()
    {
        return [
            'zh-hans-cn' => locale_get_display_region('zh-hans-cn'),
            'zh-hans-cn_zh-cn' => locale_get_display_region('zh-hans-cn', 'zh-cn'),
            'zh-hans-cn_zh-tw' => locale_get_display_region('zh-hans-cn', 'zh-tw'),
        ];
    }
    
    /**
     * 获取 显示 脚本
     * 返回输入语言环境脚本的适当本地化显示名称
     * @param string $locale 用于返回显示区域的区域设置
     * @param string $in_locale 用于显示区域名称的可选格式区域设置
     * @return string 以适合$in_locale的格式显示$locale的脚本名称
     */
    public function testGetDisplayScript()
    {
        return [
            'zh-hans-cn' => locale_get_display_script('zh-hans-cn'),
            'zh-hans-cn_zh-cn' => locale_get_display_script('zh-hans-cn', 'zh-cn'),
            'zh-hans-cn_zh-tw' => locale_get_display_script('zh-hans-cn', 'zh-tw'),
        ];
    }
    
    /**
     * 获取 显示 变种
     * 返回输入语言环境脚本的适当本地化显示名称
     * @param string $locale 用于返回显示区域的区域设置
     * @param string $in_locale 用于显示区域名称的可选格式区域设置
     * @return string 以适合$in_locale的格式显示$locale的脚本名称
     */
    public function testGetDisplayVariant()
    {
        return [
            'sl-Latn-IT-nedis_en' => locale_get_display_variant('sl-Latn-IT-nedis', 'en'),
            'sl-Latn-IT-nedis_fr' => locale_get_display_variant('sl-Latn-IT-nedis', 'fr'),
            'sl-Latn-IT-nedis_de' => locale_get_display_variant('sl-Latn-IT-nedis', 'de'),
        ];
    }
    
    /**
     * 获取输入语言环境的关键字
     * @param string $locale 从中提取关键字的区域设置
     * @return array 包含此语言环境的关键字 - 值对的 关联数组
     */
    public function testGetKeywords()
    {
        return locale_get_keywords('de_DE@currency=EUR;collation=PHONEBOOK');
    }
    
    /**
     * 获取输入语言环境的主要语言
     * @param string $locale 从中提取主要语言代码的语言环境
     * @return string 与语言相关的语言代码或NULL出错的语言代码。
     */
    public function testGetPrimaryLanguage()
    {
        return locale_get_primary_language('zh-Hant'); // zh
    }
    
    /**
     * 获取输入语言环境的区域
     * @param string $locale 从中提取区域代码的区域设置
     * @return string 区域设置的区域子标签，NULL如果不存在
     */
    public function testGetRegion()
    {
        return locale_get_region('de-CH-1901'); // ch
    }
    
    /**
     * 获取输入语言环境的脚本
     * @param string $locale 从中提取区域代码的区域设置
     * @return string 语言环境的脚本子标签，NULL如果不存在
     */
    public function testGetScript()
    {
        return locale_get_script('sr-Cyrl');
    }
    
    /**
     * 搜索语言标记列表以获得与语言的最佳匹配
     * @param array $langtag 一个数组包含语言标签的列表来比较 locale。最多允许100件商品。
     * @param string $locale 匹配时用作语言范围的语言环境
     * @param bool $canonicalize 如果为true，则在匹配之前将参数转换为规范形式
     * @param string $default 如果找不到匹配项，则使用的语言环境
     * @return string 最接近的匹配语言标记或默认值
     */
    public function testLookup()
    {
        $arr = [
            'de-DEVA',
            'de-DE-1996',
            'de',
            'de-De'
        ];
        return locale_lookup($arr, 'de-DE-1996-x-prv1-prv2', true, 'en_US');
    }
    
    /**
     * 返回区域设置ID子标签元素的键值数组
     * @param string $locale
     * @return array 
     */
    public function testParseLocale()
    {
        return locale_parse('sl-Latn-IT-nedis');
    }
}

var_export(LocaleTest::runTestUnit());