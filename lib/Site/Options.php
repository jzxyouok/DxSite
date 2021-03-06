<?php

/**
 * Class Site_Options
 * @package Site
 */
class Site_Options
{
    /**
     * @options
     */
    public static $options;

    /**
     * 初始化设置
     * @return bool
     */
    public static function init()
    {
        if (Cache::has('SiteOption')) {
           self::$options = Cache::get('SiteOption');
        } elseif ($options=self::refresh()) {
            self::$options =$options;
        } else {
            return false;
        }
        return true;
    }
    
    /**
     * 获取全部设置
     * @return mixed
     */
    public static function getOptions()
    {
        return self::$options;
    }
    public static function getTheme()
    {
        return self::$options['theme'];
    }
    /**
     * 更新设置
     * @return bool
     */
    public static function refresh():bool
    {
        $sql = 'SELECT * FROM `#{site_options}`';
        $q = new Query($sql);
        $options = $q->fetchAll();
        foreach ($options as $option) {
            self::$options[$option['name']] = $option['value'];
        }
        self::$options['powered'] = 'DxSite '.CORE_VERSION;
        self::$options['poweredUrl'] = 'https://github.com/DXkite/DxSite/releases/latest';
        Cache::set('SiteOption', self::$options, 0);
        return $q->erron() === 0;
    }
    public static function getSiteOptions()
    {
        $sql = 'SELECT * FROM `#{site_options}`';
        $q = new Query($sql);
        return  $q->fetchAll();
    }
    public static function setOption(string $name, string $value)
    {
        $sql='UPDATE `#{site_options}` SET `value`=:value WHERE `name`=:name LIMIT 1;';
        return (new Query($sql, ['name'=>$name, 'value'=>$value]))->exec();
    }
    /**
     * 魔术方法获取设置
     * @param string $name
     * @return string|null|mixed
     */
    public function __get(string $name)
    {
        return isset(self::$options[$name]) ? self::$options[$name] : null;
    }

    /**
     * 魔术方法设置值
     * @param string $name
     * @param $value
     * @return null
     */
    public function __set(string $name, $value)
    {
        // __set 方法PHP无法获取返回值
        /*return*/self::$options[$name] = $value;
    }
    public function __isset(string $name):bool
    {
        return isset(self::$options[$name]);
    }
    public function __call(string $name, $args)
    {
        $fmt=isset(self::$options[$name])?self::$options[$name]:(isset($args[0])?$args[0]:'U:['.$name.']');
        if (count($args)>1) {
            $args[0]=$fmt;
            return call_user_func_array('sprintf', $args);
        }
        return $fmt;
    }
}
