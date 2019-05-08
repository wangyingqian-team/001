<?php
namespace App\Daos\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Liviator\Exception\LiviatorException;
use Liviator\Exception\OperationFailedException;
use Wangyingqian\AliChat\Support\Log;

abstract class CommonDao
{
    /**
     * @var int
     */
    protected static $limit = 500;

    /**
     * 通用 允许查询的字段
     *
     * @var array
     */
    protected static $easyFields = [];

    /**
     * 默认查询字段
     *
     * @var array
     */
    protected static $defaultFields = [];

    /**
     * 通用 可供查询的条件
     *
     * @var array
     */
    protected static $easyFilters = [];

    /**
     * 通用 可用排序字段
     *
     * @var array
     */
    protected static $easyOrderBys = [];

    /**
     * 创建查询对象
     *
     * @return mixed
     */
    protected static abstract function createQuery();


    /**
     * 获取查询对象
     *
     * @param array $filters
     * @param array $fields
     * @param array $orderBys
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public static function easyQuery(array $filters = [], array $fields = [], array $orderBys = [])
    {
        $qb = static::createQuery();

        if (empty($fields) && !empty(static::$defaultFields)) {
            $fields = static::$defaultFields;
        }

        $qb = query_select($qb, $fields, static::$easyFields);
        $qb = query_filter($qb, $filters, static::$easyFilters);
        $qb = query_order_by($qb, $orderBys, static::$easyOrderBys);

        return $qb;
    }


    /**
     * 获取商品列表
     *
     * @param array $filters
     * @param array $fields
     * @param array $orderBys
     * @param $page
     * @param $pageSize
     *
     * @return array
     */
    public static function getEasyPageList($filters, $fields = [], $orderBys, $page, $pageSize = 20)
    {
        try {
            $qb = self::easyQuery($filters, $fields, $orderBys);
            $total = $qb->count();
            /** @var Model $model */
            $model = $qb->forPage($page, $pageSize)->get();

        } catch (ModelNotFoundException $e) {
            throw new OperationFailedException('找不到查询对象', $e);
        } catch (LiviatorException | \LogicException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::warning('查询分页列表失败', [
                'fields' => $fields,
                'filters' => $filters,
                'order_by' => $orderBys,
                'exception' => $e,
            ]);

            throw new OperationFailedException('查询分页列表失败', $e);
        }

        $list = $model->toArray();
        return [
            'list'        => $list,
            'count'       => count($list),
            'total_count' => $total,
        ];
    }


    /**
     * 获取商品列表
     *
     * @param array $filters
     * @param array $fields
     * @param array $orderBys
     * @param $limit
     * @param $skip
     *
     * @return array
     */
    public static function getEasyList($filters, $fields = [], $orderBys, $limit = -1, $skip = 0)
    {
        if ($limit > static::$limit) {
            throw new OperationFailedException('查询最大记录数不允许超过'.static::$limit);
        }
        $limit = $limit <= 0 ? static::$limit : $limit;

        try {
            $qb = self::easyQuery($filters, $fields, $orderBys);

            /** @var Model $model */
            $model = $qb->skip($skip)->limit($limit)->get();

        } catch (ModelNotFoundException $e) {
            throw new OperationFailedException('找不到查询对象', $e);
        } catch (LiviatorException|\LogicException$e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::warning('查询列表失败', [
                'fields' => $fields,
                'filters' => $filters,
                'order_by' => $orderBys,
                'exception' => $e,
            ]);

            throw new OperationFailedException('查询列表失败', $e);
        }

        return $model->toArray();
    }



    /**
     * 获取商品列表
     *
     * @param array $filters
     * @param array $fields
     * @param array $orderBys
     * @param $limit
     * @param $skip
     *
     * @return array
     */
    public static function getUnlimitedItemList($filters, $fields = [], $orderBys, $limit = -1, $skip = 0)
    {
        try {
            $qb = self::easyQuery($filters, $fields, $orderBys);


            /** @var Model $model */
            if ($limit > 0) {
                $qb->limit($limit)->skip($skip);
            }

            $model = $qb->get();

        } catch (ModelNotFoundException $e) {
            throw new OperationFailedException('找不到查询对象', $e);
        } catch (LiviatorException|\LogicException$e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::warning('查询列表失败', [
                'fields' => $fields,
                'filters' => $filters,
                'order_by' => $orderBys,
                'exception' => $e,
            ]);

            throw new OperationFailedException('查询列表失败', $e);
        }

        return $model->toArray();
    }


    /**
     * 获取单个商品
     *
     * @param array $filters
     * @param array $fields
     *
     * @return array
     */
    public static function getEasyOne($filters = [], $fields = [])
    {

        try {
            $qb = self::easyQuery($filters, $fields, []);

            /** @var Model $model */
            $model = $qb->first();

        } catch (ModelNotFoundException $e) {
            throw new OperationFailedException('找不到查询对象', $e);
        } catch (LiviatorException|\LogicException$e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::warning('获取单条记录失败', [
                'fields' => $fields,
                'filters' => $filters,
                'exception' => $e
            ]);

            throw new OperationFailedException('获取单条记录失败', $e);
        }

        return empty($model) ? [] : $model->toArray();
    }


    /**
     * 统计
     *
     * @param array $filters
     *
     * @return array
     */
    public static function easyCount($filters = [])
    {

        try {

            $qb = static::createQuery();
            $qb = query_filter($qb, $filters, static::$easyFilters);

            /** @var Model $model */
            $count = $qb->count();

        } catch (ModelNotFoundException $e) {
            throw new OperationFailedException('找不到查询对象', $e);
        } catch (LiviatorException|\RuntimeException$e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::warning('统计记录失败', [
                'filters' => $filters,
                'exception' => $e
            ]);

            throw new OperationFailedException('统计记录失败', $e);
        }

        return $count;
    }



    /**
     * 分页
     *
     * @param Builder $query
     * @param int $page
     * @param int $pageSize
     *
     * @return array
     */
    public static function page(Builder $query, $page = 0, $pageSize = 20)
    {
        $total = $query->count();

        if ($page < 0) {
            $list = $query->limit(static::$limit)->get()->toArray();
        } else {
            $list = $query->forPage($page, $pageSize)->get()->toArray();
        }

        return [
            'list' => $list,
            'total_count' => $total,
            'count' => count($list)
        ];
    }


    /**
     * 获得 SQL 语句占位符
     *
     * @param array $values
     *
     * @return string
     */
    protected static function parameterize($values)
    {
        return implode(', ', array_pad([], count($values), '?'));
    }

    /**
     * 格式化字段 解析为with fields 结构，映射缓存数据结构
     *
     * @param array $fields
     *
     * @return array
     */
    protected static function _formatWithFields($fields)
    {
        $selectFieldMaps = [];
        foreach ($fields as $field) {
            $withFieldArr = explode('.', $field);
            if (count($withFieldArr) == 1) {
                $withField = reset($withFieldArr);
                $selectFieldMaps['base'][] = $withField;
            } else {
                list($with, $withField) = $withFieldArr;
                $selectFieldMaps[$with][] = $withField;
            }
        }
        return $selectFieldMaps;
    }
}