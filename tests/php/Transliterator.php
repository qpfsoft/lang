<?php
use qpf\deunit\TestUnit;

include __DIR__ . '/../boot.php';

class TransliteratorTest extends TestUnit
{
    public function TestTransliterate()
    {
        $s = "\u304A\u65E9\u3046\u3054\u3056\u3044\u307E\u3059";
        return transliterator_transliterate("Hex-Any/Java", $s);
    }
    
    public function testTransliterator_create_inverse()
    {
        $transliterator = transliterator_create('zh');
        
        // 创建逆音译器
        return transliterator_create_inverse($transliterator);
    }
}

var_export(TransliteratorTest::runTestUnit());