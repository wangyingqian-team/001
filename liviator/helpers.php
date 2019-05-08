<?php

use Liviator\Support\QueryHelper;

if (!function_exists('query_select')) {
    /**
     * 选择处理
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $qb
     * @param array $fields
     * @param array $selectables
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    function query_select($qb, array $fields, array $selectables)
    {
        return QueryHelper::select($qb, $fields, $selectables);
    }
}

if (!function_exists('query_filter')) {
    /**
     * 过滤处理
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $qb
     * @param array $filters
     * @param array $filterables
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    function query_filter($qb, array $filters, array $filterables)
    {
        return QueryHelper::filter($qb, $filters, $filterables);
    }
}

if (!function_exists('query_order_by')) {
    /**
     * 排序处理
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $qb
     * @param array $orderBy
     * @param array $orderables
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    function query_order_by($qb, array $orderBy, array $orderables)
    {
        return QueryHelper::orderBy($qb, $orderBy, $orderables);
    }
}

if (!function_exists('query_skip_limit')) {
    /**
     * 限制处理
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $qb
     * @param int $skip
     * @param int $limit
     * @param int $maxLimit
     * @param bool $forceLimit
     *
     * @return \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder
     */
    function query_skip_limit($qb, $skip, $limit, $maxLimit = 100, $forceLimit = true)
    {
        return QueryHelper::skipLimit($qb, $skip, $limit, $maxLimit, $forceLimit);
    }
}

if (!function_exists('query_for_page')) {
    /**
     * 分页处理
     *
     * @param \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $qb
     * @param int $page
     * @param int $perPage
     * @param int $maxLimit
     * @param bool $forceLimit
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    function query_for_page($qb, $page, $perPage, $maxLimit = 100, $forceLimit = true)
    {
        return QueryHelper::forPage($qb, $page, $perPage, $maxLimit, $forceLimit);
    }
}
