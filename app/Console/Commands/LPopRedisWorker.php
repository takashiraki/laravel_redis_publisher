<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class LPopRedisWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:l-pop-redis-worker';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('LPopRedisWorker started.');

        while (true) {
            $result = Redis::blpop('order.queue', 0);

            if (! $result) {
                continue;
            }

            $payload = json_decode($result[1], true);
            $orderId =  $payload['order_id'] . PHP_EOL;

            // 実際の処理（例：メール送信・DB保存など）
            $this->process($orderId);

            $this->info("Handled order: {$orderId}");
        }
    }

    private function process(string $id): void
    {
        echo "Processing ID: {$id}";
    }
}
