<?php
namespace Database\Seeders;

use App\Contribute;
use App\Gold;
use App\Question;
use App\Visit;
use Illuminate\Database\Seeder;

/**
 * 专注清理无用大数据表用
 */
class CleanupSeeder extends Seeder
{
    public function run()
    {
        Question::truncate();
        Gold::truncate();
        Visit::truncate();
        Contribute::truncate();
    }
}
