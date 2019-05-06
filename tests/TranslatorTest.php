<?php
use qpf\deunit\TestUnit;
use qpf\lang\Translator;
use qpf\lang\DictionaryLangPack;
use qpf\lang\ArrayLangPack;

include 'boot.php';

class TranslatorTest extends TestUnit
{
    public $translator;
    
    public function setUp()
    {
        $this->translator = new Translator();
        
        $lang_path = __DIR__ . '/langs';
        
        $this->translator->langpacks = [
            'error' => new DictionaryLangPack($lang_path),
            'core'  => new ArrayLangPack($lang_path),
        ];
    }
    
    public function testTranslateLangMark()
    {
        return $this->translator->translate('core', 'welcome', ['name' => 'QPF'], 'zh-cn');
    }
    
    public function testTranslateDictionary()
    {
        return $this->translator->translate('error', 'Phrase is for better translation', [], 'zh-cn');
    }
}

var_export(TranslatorTest::runTestUnit());