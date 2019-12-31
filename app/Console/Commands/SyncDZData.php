<?php

namespace App\Console\Commands;

use App\Category;
use App\Comment;
use App\Question;
use App\User;
use App\Wallet;
use App\WalletTransaction;
use App\Withdraw;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Overtrue\LaravelPinyin\Facades\Pinyin;

class SyncDZData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:dz {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $conn = 'local_dtzq';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $beginTime = microtime(true);
        if ($table = $this->argument('table')) {
            $fixCount = $this->$table();
        } else {
            return $this->error("必须提供你要修复数据的table");
        }
        $this->info('总共修复数据:' . $fixCount);
        $endTime     = microtime(true);
        $processTime = $endTime - $beginTime;
        $this->info('本次修复数据,总耗时:' . $processTime);
    }

    public function categories()
    {
        $categories = DB::connection($this->conn)->table('categories')->get();
        $count      = 0;

        foreach ($categories as $dzCategory) {
            $dzCategoryAttrs = json_decode(json_encode($dzCategory), true);

            $name_en = Pinyin::sentence($dzCategory->name);
            $name_en = str_replace(' ', '', $name_en);

            $category = Category::firstOrNew([
                'name'    => $dzCategory->name,
                'name_en' => $name_en,
                'type'    => 'question',
            ]);
            $category->fill($dzCategoryAttrs);
            $category->logo_app  = $dzCategory->icon;
            $category->parent_id = 0;
            $category->save();
            $count++;

            $this->info('Category Id:' . $category->id . ' fix success');
        }

        return $count;
    }

    public function questions()
    {
        ini_set('memory_limit', '-1');
        $count     = 0;
        $questions = DB::connection($this->conn)->table('questions')->orderBy('id')->chunk(10000, function ($questions) use (&$count) {
            foreach ($questions as $question) {
                try {
                    $question      = json_encode($question);
                    $question      = json_decode($question, true);
                    $questionModel = (new Question)->fill($question);
                    if (is_null($questionModel->submit)) {
                        $question->submit = 0;
                    }

                    $questionModel->save();
                } catch (\Exception $ex) {
                    continue;
                }
                $count++;
                $this->info('Question ID:' . $questionModel->id . ' sync success');
            }
        });

        return $count;
    }

    public function users()
    {
        ini_set('memory_limit', '-1');
        $count = 0;

        $wallteIds = DB::connection($this->conn)->table('withdraws')
            ->select('wallet_id')
            ->groupBy('wallet_id')
            ->get()
            ->pluck('wallet_id');

        $userIdsArr = DB::connection($this->conn)->table('wallets')
            ->select('user_id')
            ->whereIn('id', $wallteIds)
            ->get()
            ->pluck('user_id')
            ->toArray();
        $userChunkIdsArr = array_chunk($userIdsArr, 10000);

        //分片写入提现用户
        foreach ($userChunkIdsArr as $userIds) {
            $users = DB::connection($this->conn)
                ->table('users')
                ->whereIn('id', $userIds)
                ->get();

            //TODO:需要兼容头像解析方案
            foreach ($users as $user) {
                $dmgUser = User::firstOrNew(['dz_id' => $user->id]);
                if (!isset($dmgUser->id)) {
                    $userArr = json_decode(json_encode($user), true);
                    $dmgUser->fill($userArr);
                    $dmgUser->account = 'fix:' . $userArr['account'];
                    $dmgUser->save();
                    $this->info('DZ USER_ID:' . $user->id . ' CREATE USER:' . $dmgUser->id);
                    $count++;
                }
            }
        }

        return $count;

    }

    public function wallets()
    {
        $count = 0;
        $limit = 10000;
        DB::connection($this->conn)->table('wallets')->orderBy('id')->chunk($limit, function ($wallets) use (&$count, $limit) {
            $walletsArr = [];

            foreach ($wallets as $wallet) {
                $walletAttr         = json_decode(json_encode($wallet), true);
                $walletAttr['type'] = 0;
                unset($walletAttr['id']);

                Wallet::Insert($walletsArr);

                $walletsArr[] = $walletAttr;
            }

            Wallet::Insert($walletsArr);
            $count += $limit;
            $this->info('成功写入钱包数据:' . $count);
        });

        return $count;
    }

    public function withdraws()
    {
        $count = 0;
        $limit = 1000;
        DB::connection($this->conn)->table('withdraws')->orderBy('id')->chunk($limit, function ($withdraws) use (&$count, $limit) {
            $withdrawsArr = [];

            foreach ($withdraws as $withdraw) {
                $withdrawAttr   = json_decode(json_encode($withdraw), true);
                $withdrawModel  = (new Withdraw)->fill($withdrawAttr);
                $withdrawsArr[] = $withdrawModel->toArray();
            }

            Withdraw::Insert($withdrawsArr);
            $count += $limit;
            $this->info('成功写入提现数据:' . $count);
        });

        return $count;
    }

    public function transactions()
    {
        $count = 0;
        $limit = 1000;
        DB::connection($this->conn)->table('transactions')->orderBy('id')->chunk($limit, function ($transactions) use (&$count, $limit) {
            $transactionsArr = [];

            foreach ($transactions as $transaction) {
                $transactionsAttr  = json_decode(json_encode($transaction), true);
                $transactionsModel = (new WalletTransaction)->fill($transactionsAttr);
                $transactionsArr[] = $transactionsModel->toArray();
            }

            WalletTransaction::Insert($transactionsArr);
            $count += $limit;
            $this->info('成功写入流水数据:' . $count);
        });

        return $count;
    }

    public function comments()
    {
        $count = 0;
        $limit = 1000;
        DB::connection($this->conn)->table('comments')->orderBy('id')->chunk($limit, function ($comments) use (&$count, $limit) {
            $commentsArr = [];

            foreach ($comments as $comment) {
                $commentAttr        = json_decode(json_encode($comment), true);
                $commentModel       = (new Comment)->fill($commentAttr);
                $commentModel->body = $commentAttr['content'] ?? "";
                $commentModel->lou  = $commentAttr['rank'];
                $commentsArr[]      = $commentModel->toArray();
            }
            Comment::Insert($commentsArr);
            $count += $limit;
            $this->info('成功写入评论数据:' . $count);
        });

        return $count;
    }
}
