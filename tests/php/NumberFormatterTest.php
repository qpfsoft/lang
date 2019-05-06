<?php
use qpf\deunit\TestUnit;

include __DIR__ . '/../boot.php';

/**
 * PHP 国际化 - 数字格式化内置类
 * 
 * 百分比, 货币, 十进制, 时间,等
 * 
 * >= 5.3.0 PHP内置
 */
class NumberFormatterTest extends TestUnit
{
    public $format;
    
    public function setUp()
    {
        if (!extension_loaded('intl')) {
            throw new \Exception('Need to install PHP intl extension');
        }
        
        $locale = 'zh-CN';
        
        /* @link https://www.php.net/manual/zh/class.numberformatter.php#intl.numberformatter-constants.unumberformatstyle */
        
        $this->format = new NumberFormatter($locale, \NumberFormatter::CURRENCY);
    }
    
    /**
     * 获取货币符号值
     */
    public function testGetCurrencySymbo()
    {
        return $this->format->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }
}

var_export(NumberFormatterTest::runTestUnit());