<?php
use qpf\deunit\TestUnit;
use qpf\lang\Lang;
use qpf\lang\Translator;
use qpf\lang\DictionaryLangPack;
use qpf\lang\ArrayLangPack;

include 'boot.php';

class LangTest extends TestUnit
{
    public $lang;
    
    public function setUp()
    {
        $lang_path = __DIR__ . '/langs';
        $this->lang = new Lang(new Translator([
            'error' => new DictionaryLangPack($lang_path),
            'core'  => new ArrayLangPack($lang_path),
        ]));
    }
    
    public function testBase1()
    {
        return $this->lang->translate('core', 'welcome', ['name'=>'qpf']);
    }
    
    public function testBase2()
    {
        return $this->lang->translate('error', 'translate  ok !');
    }
    
    
    public function testgetClientLang()
    {
        return $this->lang->auto();
    }
}

var_export(LangTest::runTestUnit());