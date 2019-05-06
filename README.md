# lang
language translation

## 多语言

网站多语言, 一般采用语句标识映射一句消息.

```php
# en
[
     'welcome' => 'Hello {name}, nice to meet you!',
]

# zh
[
    'welcome' => '你好 {name}, 见到你很高兴!',
]
```

根据语言类型, 分包映射对应的语言消息. 

## 目录结构

```txt
/root
   |- langs
   |    |- zh-cn
   |    |   |- simple
   |    |   |- precision
   |    |- en-us
   |    |   |- simple
   |    |   |- precision
```

可将语言包按类别划分, 这样可根据app或class获取自身需要的语言. 不会一次加载所有语言消息.

## 示例

请自行查看`tests`目录内示例.

## 翻译

网站多语言, 使用语言标记的好处是, 不用做词库(词库size很大), 翻译的语句更准确!

不过还是提供了 词典语言包, 以英文单词为基准, 直接替换翻译, 词典不知道的单词, 将原样返回.

由于语法顺序问题, 也可使用短语, 但建议依赖单词, 而不是短语.

词典语言包格式:

- 短语与单词定义不区分大小写

```php
[
	'phrase'    => [
        'Phrase is for better translation'  => '短语是为了更好的翻译',
    ],
    
    'word'      => [
        't' => [
            'translate' => '翻译', // 推荐, 首字母分组
        ],
        
        'translate' => '翻译', // 若存在首字符分组, 该词将无效
        
    ],
]
```

