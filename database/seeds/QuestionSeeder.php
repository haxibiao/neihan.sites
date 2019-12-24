<?php

use Illuminate\Database\Seeder;
use App\Topic;
use App\User;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $question_titles = [
            '什么句子最让你惊艳?',
            '推荐一些伤感的句子？',
            '优美的诗句有哪些？',
            '最适合当个性签名的句子有哪些？',
            '有哪些不为人知的冷知识？',
            '你见过哪些动人的情诗？',
            '席慕容的诗句哪些最打动你？',
            '简帧的诗句哪些最经典？',
            '有没有聚会时可以用的游戏？',
            '有没有2018最新搞笑脑筋急转弯？',
            '有没有幽默搞笑冷笑话，适合在聚会时活跃气氛的！',
            '求天线宝宝表情包~',
            '幽默搞笑的句子，最好是新的~谢谢~',
            '小清新的句子，适合当个性签名的~',
        ];

        $users=User::whereBetween('id',[23,123])->get();

        foreach($question_titles as $question_title){
        	$question=Topic::firstOrNew([
        		'title'=>$question_title,
        	]);

        	$question->user_id=$users->random()->id;

        	$question->save();
        }

    }
}
