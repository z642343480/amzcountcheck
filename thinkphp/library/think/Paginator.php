<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangyajun <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

abstract class Paginator implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * 是否简洁模式
     * @var bool
     */
    protected $simple = false;

    /**
     * 数据集
     * @var Collection
     */
    protected $items;

    /**
     * 当前页
     * @var integer
     */
    protected $currentPage;

    /**
     * 最后一页
     * @var integer
     */
    protected $lastPage;

    /**
     * 数据总数
     * @var integer|null
     */
    protected $total;

    /**
     * 每页数量
     * @var integer
     */
    protected $listRows;

    /**
     * 是否有下一页
     * @var bool
     */
    protected $hasMore;

    /**
     * 分页配置
     * @var array
     */
    protected $options = [
        'var_page' => 'page',
        'path'     => '/',
        'query'    => [],
        'fragment' => '',
    ];

    public function __construct($items, $listRows, $currentPage = null, $total = null, $simple = false, $options = [])
    {
        $this->options = array_merge($this->options, $options);

        $this->options['path'] = '/' != $this->options['path'] ? rtrim($this->options['path'], '/') : $this->options['path'];

        $this->simple   = $simple;
        $this->listRows = $listRows;

        if (!$items instanceof Collection) {
            $items = Collection::make($items);
        }

        if ($simple) {
            $this->currentPage = $this->setCurrentPage($currentPage);
            $this->hasMore     = count($items) > ($this->listRows);
            $items             = $items->slice(0, $this->listRows);
        } else {
            $this->total       = $total;
            $this->lastPage    = (int) ceil($total / $listRows);
            $this->currentPage = $this->setCurrentPage($currentPage);
            $this->hasMore     = $this->currentPage < $this->lastPage;
        }
        $this->items = $items;
    }

    /**
     * @access public
     * @param       $items
     * @param       $listRows
     * @param null  $currentPage
     * @param null  $total
     * @param bool  $simple
     * @param array $options
     * @return Paginator
     */
    public static function make($items, $listRows, $currentPage = null, $total = null, $simple = false, $options = [])
    {
        return new static($items, $listRows, $currentPage, $total, $simple, $options);
    }

    protected function setCurrentPage($currentPage)
    {
        if (!$this->simple && $currentPage > $this->lastPage) {
            return $this->lastPage > 0 ? $this->lastPage : 1;
        }

        return $currentPage;
    }

    /**
     * 获取页码对应的链接
     *
     * @access protected
     * @param  $page
     * @return string
     */
    protected function url($page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        if (strpos($this->options['path'], '[PAGE]') === false) {
            $parameters = [$this->options['var_page'] => $page];
            $path       = $this->options['path'];
        } else {
            $parameters = [];
            $path       = str_replace('[PAGE]', $page, $this->options['path']);
        }

        if (count($this->options['query']) > 0) {
            $parameters = array_merge($this->options['query'], $parameters);
        }

        $url = $path;
        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters, null, '&');
        }

        return $url . $this->buildFragment();
    }

    /**
     * 自动获取当前页码
     * @access public
     * @param  string $varPage
     * @param  int    $default
     * @return int
     */
    public static function getCurrentPage($varPage = 'page', $default = 1)
    {
        $page = Container::get('request')->param($varPage);

        if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
            return $page;
        }

        return $default;
    }

    /**
     * 自动获取当前的path
     * @access public
     * @return string
     */
    public static function getCurrentPath()
    {
        return Container::get('request')->baseUrl();
    }

    public function total()
    {
        if ($this->simple) {
            throw new \DomainException('not support total');
        }

        return $this->total;
    }

    public function listRows()
    {
        return $this->listRows;
    }

    public function currentPage()
    {
        return $this->currentPage;
    }

    public function lastPage()
    {
        if ($this->simple) {
            throw new \DomainException('not support last');
        }

        return $this->lastPage;
    }

    /**
     * �<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: zhangyajun <448901948@qq.com>
// +----------------------------------------------------------------------

namespace think;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Traversable;

abstract class Paginator implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable
{
    /**
     * 是否简洁模式
     * @var bool
     */
    protected $simple = false;

    /**
     * 数据集
     * @var Collection
     */
    protected $items;

    /**
     * 当前页
     * @var integer
     */
    protected $currentPage;

    /**
     * 最后一页
     * @var integer
     */
    protected $lastPage;

    /**
     * 数据总数
     * @var integer|null
     */
    protected $total;

    /**
     * 每页数量
     * @var integer
     */
    protected $listRows;

    /**
     * 是否有下一页
     * @var bool
     */
    protected $hasMore;

    /**
     * 分页配置
     * @var array
     */
    protected $options = [
        'var_page' => 'page',
        'path'     => '/',
        'query'    => [],
        'fragment' => '',
    ];

    public function __construct($items, $listRows, $currentPage = null, $total = null, $simple = false, $options = [])
    {
        $this->options = array_merge($this->options, $options);

        $this->options['path'] = '/' != $this->options['path'] ? rtrim($this->options['path'], '/') : $this->options['path'];

        $this->simple   = $simple;
        $this->listRows = $listRows;

        if (!$items instanceof Collection) {
            $items = Collection::make($items);
        }

        if ($simple) {
            $this->currentPage = $this->setCurrentPage($currentPage);
            $this->hasMore     = count($items) > ($this->listRows);
            $items             = $items->slice(0, $this->listRows);
        } else {
            $this->total       = $total;
            $this->lastPage    = (int) ceil($total / $listRows);
            $this->currentPage = $this->setCurrentPage($currentPage);
            $this->hasMore     = $this->currentPage < $this->lastPage;
        }
        $this->items = $items;
    }

    /**
     * @access public
     * @param       $items
     * @param       $listRows
     * @param null  $currentPage
     * @param null  $total
     * @param bool  $simple
     * @param array $options
     * @return Paginator
     */
    public static function make($items, $listRows, $currentPage = null, $total = null, $simple = false, $options = [])
    {
        return new static($items, $listRows, $currentPage, $total, $simple, $options);
    }

    protected function setCurrentPage($currentPage)
    {
        if (!$this->simple && $currentPage > $this->lastPage) {
            return $this->lastPage > 0 ? $this->lastPage : 1;
        }

        return $currentPage;
    }

    /**
     * 获取页码对应的链接
     *
     * @access protected
     * @param  $page
     * @return string
     */
    protected function url($page)
    {
        if ($page <= 0) {
            $page = 1;
        }

        if (strpos($this->options['path'], '[PAGE]') === false) {
            $parameters = [$this->options['var_page'] => $page];
            $path       = $this->options['path'];
        } else {
            $parameters = [];
            $path       = str_replace('[PAGE]', $page, $this->options['path']);
        }

        if (count($this->options['query']) > 0) {
            $parameters = array_merge($this->options['query'], $parameters);
        }

        $url = $path;
        if (!empty($parameters)) {
            $url .= '?' . http_build_query($parameters, null, '&');
        }

        return $url . $this->buildFragment();
    }

    /**
     * 自动获取当前页码
     * @access public
     * @param  string $varPage
     * @param  int    $default
     * @return int
     */
    public static function getCurrentPage($varPage = 'page', $default = 1)
    {
        $page = Container::get('request')->param($varPage);

        if (filter_var($page, FILTER_VALIDATE_INT) !== false && (int) $page >= 1) {
            return $page;
        }

        return $default;
    }

    /**
     * 自动获取当前的path
     * @access public
     * @return string
     */
    public static function getCurrentPath()
    {
        return Container::get('request')->baseUrl();
    }

    public function total()
    {
        if ($this->simple) {
            throw new \DomainException('not support total');
        }

        return $this->total;
    }

    public function listRows()
    {
        return $this->listRows;
    }

    public function currentPage()
    {
        return $this->currentPage;
    }

    public function lastPage()
    {
        if ($this->simple) {
            throw new \DomainException('not support last');
        }

        return $this->lastPage;
    }

    /**
     * 数据是否足够分页
     * @access public
     * @return boolean
     */
    public function hasPages()
    {
        return !(1 == $this->currentPage && !$this->hasMore);
    }

    /**
     * 创建一组分页链接
     *
     * @access public
     * @param  int $start
     * @param  int $end
     * @return array
     */
    public function getUrlRange($start, $end)
    {
        $urls = [];

        for ($page = $start; $page <= $end; $page++) {
            $urls[$page] = $this->url($page);
        }

        return $urls;
    }

    /**
     * 设置URL锚点
     *
     * @access public
     * @param  string|null $fragment
     * @return $this
     */
    public function fragment($fragment)
    {
        $this->options['fragment'] = $fragment;

        return $this;
    }

    /**
     * 添加URL参数
     *
     * @access public
     * @param  array|string $key
     * @param  string|null  $value
     * @return $this
     */
    public function appends($key, $value = null)
    {
        if (!is_array($key)) {
            $queries = [$key => $value];
        } else {
            $queries = $key;
        }

        foreach ($queries as $k => $v) {
            if ($k !== $this->options['var_page']) {
                $this->options['query'][$k] = $v;
            }
        }

        return $this;
    }

    /**
     * 构造锚点字符串
     *
     * @access public
     * @return string
     */
    protected function buildFragment()
    {
        return $this->options['fragment'] ? '#' . $this->options['fragment'] : '';
    }

    /**
     * 渲染分页html
     * @access public
     * @return mixed
     */
    abstract public function render();

    public function items()
    {
        return $this->items->all();
    }

    public function getCollection()
    {
        return $this->items;
    }

    public function isEmpty()
    {
        return $this->items->isEmpty();
    }

    /**
     * 给每个元素执行个回调
     *
     * @access public
     * @param  callable $callback
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->items as $key => $item) {
            $result = $callback($item, $key);

            if (false === $result) {
                break;
            } elseif (!is_object($item)) {
                $this->items[$key] = $result;
            }
        }

        return $this;
    }

    /**
     * Retrieve an external iterator
     * @access public
     * @return Traversable An instance of an object implementing <b>Iterator</b> or
     * <b>Traversable</b>
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items->all());
    }

    /**
     * Whether a offset exists
     * @access public
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->items->offsetExists($offset);
    }

    /**
     * Offset to retrieve
     * @access public
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->items->offsetGet($offset);
    }

    /**
     * Offset to set
     * @access public
     * @param  mixed $offset
     * @param  mixed $value
     */
    public function offsetSet($offset, $value)
    {
        $this->items->offsetSet($offset, $value);
    }

    /**
     * Offset to unset
     * @access public
     * @param  mixed $offset
     * @return void
     * @since  5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->items->offsetUnset($offset);
    }

    /**
     * Count elements of an object
     */
    public function count()
    {
        return $this->items->count();
    }

    public function __toString()
    {
        return (string) $this->render();
    }

    public function toArray()
    {
        try {
            $total = $this->total();
        } catch (\DomainException $e) {
            $total = null;
        }

        return [
            'total'        => $total,
            'per_page'     => $this->listRows(),
            'current_page' => $this->currentPage(),
            'last_page'    => $this->lastPage,
            'data'         => $this->items->toArray(),
        ];
    }

    /**
     * Specify data which should be serialized to JSON
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function __call($name, $arguments)
    {
        $collection = $this->getCollection();

        $result = call_user_func_array([$collection, $name], $arguments);

        if ($result === $collection) {
            return $this;
        }

        return $result;
    }

}
