<?php
namespace Liviator\Whisper\Concern;

use Illuminate\Support\Facades\Log;
use Redis;
use Throwable;

trait ClearTransaction
{
    /**
     * 清理事务
     *
     * @param \Lawoole\Contracts\Foundation\Application $app
     */
    protected function clearTransaction($app)
    {
        $this->clearDatabaseTransaction($app);

        $this->clearRedisTransaction($app);
    }

    /**
     * 清理数据库事务
     *
     * @param \Lawoole\Contracts\Foundation\Application $app
     */
    protected function clearDatabaseTransaction($app)
    {
        if (!$app->resolved('db')) {
            return;
        }

        $db = $app->make('db');

        if ($db->transactionLevel() > 0) {
            Log::warning('调用存在未完成的数据库事务，已执行回滚', [
                'transaction' => $db->transactionLevel()
            ]);

            $db->rollBack();
        }
    }

    /**
     * 清理 Redis 事务
     *
     * @param \Lawoole\Contracts\Foundation\Application $app
     */
    protected function clearRedisTransaction($app)
    {
        if (!$app->resolved('redis')) {
            return;
        }

        $redis = $app->make('redis');

        try {
            if ($redis->getMode() != Redis::ATOMIC) {
                Log::warning('调用存在未完成的 Redis 串行过程，已执行回滚', [
                    'mode' => $redis->getMode()
                ]);

                $redis->discard();
            }

            $redis->ping();
        } catch (Throwable $e) {
            try {
                $redis->close();
            } catch (Throwable $e) {
                //
            }

            $app->forgetInstance('redis');
        }
    }
}