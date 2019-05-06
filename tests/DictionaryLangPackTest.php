<?php
use qpf\deunit\TestUnit;
use qpf\lang\DictionaryLangPack;

include 'boot.php';

class DictionaryLangPackTest extends TestUnit
{
    public $langs;
    
    public function setUp()
    {
        $this->langs = new DictionaryLangPack(__DIR__ . '/langs');
    }
    
    public function testTranslate1()
    {
        return $this->langs->translate('error', 'Translate a word', 'zh-cn');
    }
    
    public function testTranslate2()
    {
        return $this->langs->translate('error', 'Phrase is for better translation', 'zh-cn');
    }
}

var_export(DictionaryLangPackTest::runTestUnit());