<?php
use qpf\deunit\TestUnit;
use qpf\lang\ArrayLangPack;

include 'boot.php';

class ArrayLangPackTest extends TestUnit
{
    /**
     * 语言包
     * @var ArrayLangPack
     */
    public $langs;
    
    public function setUp()
    {
        $lang_path = __DIR__ . '/langs';
        $this->langs = new ArrayLangPack($lang_path);
    }
    
    public function testloadZhCoreLangPack()
    {
        return $this->langs->getDicts('core', 'zh-cn');
    }
    
    public function testTranslateWelcomeForZh()
    {
        return $this->langs->translate('core', 'welcome', 'zh-cn');
    }
    
    public function testTranslateWelcomeForEn()
    {
        return $this->langs->translate('core', 'welcome', 'en-us');
    }
    
    public function testArrayLangPack()
    {
        return var_export($this->langs, true);
    }
}

var_export(ArrayLangPackTest::runTestUnit());