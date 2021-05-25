<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\facade;

use think\Facade;

/**
 * @see \think\Log
 * @mixin \think\Log
 * @method \think\Log init(array $config = []) static 日志初始化
 * @method mixed getLog(string $type = '') static 获取日志信息
 * @method \think\Log record(mixed $msg, string $type = 'info', array $context = []) static 记录日志信息
 * @method \think\Log clear() static 清空日志信息
 * @method \think\Log key(string $key) static 当前日志记录的授权key
 * @method \think\Log close() static 关闭本次请求日志写入
 * @method bool check(array $config) static 检查日志写入权限
 * @method bool save() static 保存调试信息
 * @method void write(mixed $msg, string $type = 'info', bool $force = false) static 实时写入日志信息
 * @method void log(string $level,mixed $message, array $context = []) static 记录日志信息
 * @method void emergency(mixed $message, array $context = []) static 记录emergency信息
 * @method void alert(mixed $message, array $context = []) static 记录alert信息
 * @method void critical(mixed $message, array $context = []) static 记录critical信息
 * @method void error(mixed $message, array $context = []) static �<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace think\facade;

use think\Facade;

/**
 * @see \think\Log
 * @mixin \think\Log
 * @method \think\Log init(array $config = []) static 日志初始化
 * @method mixed getLog(string $type = '') static 获取日志信息
 * @method \think\Log record(mixed $msg, string $type = 'info', array $context = []) static 记录日志信息
 * @method \think\Log clear() static 清空日志信息
 * @method \think\Log key(string $key) static 当前日志记录的授权key
 * @method \think\Log close() static 关闭本次请求日志写入
 * @method bool check(array $config) static 检查日志写入权限
 * @method bool save() static 保存调试信息
 * @method void write(mixed $msg, string $type = 'info', bool $force = false) static 实时写入日志信息
 * @method void log(string $level,mixed $message, array $context = []) static 记录日志信息
 * @method void emergency(mixed $message, array $context = []) static 记录emergency信息
 * @method void alert(mixed $message, array $context = []) static 记录alert信息
 * @method void critical(mixed $message, array $context = []) static 记录critical信息
 * @method void error(mixed $message, array $context = []) static 记录error信息
 * @method void warning(mixed $message, array $context = []) static 记录warning信息
 * @method void notice(mixed $message, array $context = []) static 记录notice信息
 * @method void info(mixed $message, array $context = []) static 记录info信息
 * @method void debug(mixed $message, array $context = []) static 记录debug信息
 * @method void sql(mixed $message, array $context = []) static 记录sql信息
 */
class Log extends Facade
{
    /**
     * 获取当前Facade对应类名（或者已经绑定的容器对象标识）
     * @access protected
     * @return string
     */
    protected static function getFacadeClass()
    {
        return 'log';
    }
}
