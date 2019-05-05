<?php
namespace App\Daos;

use App\Models\Payment\BillModel;
use App\Supports\QueryHelper;

/**
 * 数据库的CURD
 *
 * Class PaymentDao
 *
 * @package App\Daos
 */
class BillDao
{
    public function getBillInfo()
    {
        return QueryHelper::easyQuery(BillModel::query(), ['id'], ['id|!='=>10], ['id'=>'desc']);
    }
}