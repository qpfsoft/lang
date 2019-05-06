<?php
namespace qpf\lang;

/**
 * 基于数组格式的语言包
 */
class ArrayLangPack extends LangPack
{
    /**
     * 语言包目录路径
     * @var string
     */
    public $path;
    /**
     * 类别与文件路径映射
     * ```
     * [
     *      'base'  => 'base_qpf.php'
     * ]
     * ```
     * @var array
     */
    public $map = [];
    
    /**
     * 构造函数
     * @param string $path 目录
     * @param array $map 类别与文件映射
     */
    public function __construct($path = null, array $map = [])
    {
        !empty($path) && $this->setPath($path);
        $this->setMap($map);
    }
    
    public function setPath($path)
    {
        if (!is_dir($path)) {
            throw new \Exception($path . ' lang pack dir not exist');
        }
        $this->path = $path;
    }
    
    public function getPath()
    {
        return $this->path;
    }
    
    public function setMap(array $map)
    {
        if (!empty($map)) {
            $this->map = array_merge($this->map, $map);
        }
    }
    
    public function getMap()
    {
        return $this->map;
    }
    
    /**
     * 加载语言包
     * @return array
     */
    protected function land($type, $lang)
    {
        $file = $this->getPackPath($type, $lang);

        return (array) $this->loadPackFile($file);
    }
    
    /**
     * 获取语言包类别文件路径
     * @param string $type 类别
     * @param string $lang 语言
     * @return string
     */
    protected function getPackPath($type, $lang)
    {
        $file = $this->path . '/' . $lang . '/';
        if (isset($this->map[$type])) {
            $file .= $this->map[$type];
        } else {
            $file .= str_replace('\\', '/', $type) . '.php';
        }
        
        return $file;
    }
    
    /**
     * 加载语言包类别文件
     * @param string $file 文件路径
     * @return array|null
     */
    protected function loadPackFile($file)
    {
        if (is_file($file)) {
            $langs = include $file;
            if (!is_array($langs)) {
                $langs = [];
            }
            
            return $langs;
        }
        
        return null;
    }
}