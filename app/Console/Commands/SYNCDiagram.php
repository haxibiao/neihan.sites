<?php

namespace App\Console\Commands;

use App\Article;
use App\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SYNCDiagram extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:diagram {host?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $host = $this->argument('host');
        // 拿到丢碟数据
        DB::connection($host)->table('diagrams')->orderBy('id','asc')->whereBetween('id',[1,6140])->whereStatus(0)->chunk(100,function($diagrams) use($host){
             foreach ($diagrams as $diagram){
                 $episodes = DB::connection($host)->table('episodes')
                     ->where('diagram_id',$diagram->id)->get();
                 DB::connection('neihan_sites')->beginTransaction();
                 try {
                     // 章节
                     $category = Category::firstOrNew([
                         'name'=>$diagram->title
                     ]);
                     if(!$category->exists){
                         $category->description = $diagram->content;
                         $category->logo    =   'https://cos.diudie.com/' . $diagram->poster;
                         $category->icon    =   'https://cos.diudie.com/' . $diagram->poster;
                         $category->user_id =   random_int(1,100);
                         $category->status  = $diagram->status;
                         $category->type    = 'diagrams';
                         $category->updated_at  = $diagram->updated_at;
                         $category->created_at  = $diagram->created_at;
                         $category->saveDataOnly(['timestamps'=>false]);
                     }
                     // 入库neihan_sites category-diagram article-eposides catagorizable
                     $articleIds = [];
                     foreach ($episodes as $episode){
                        $article = Article::firstOrNew([
                            'title' => $diagram->title.':'.$episode->name,
                            'category_id'  => $category->id
                        ]);
                         $article->description = data_get(json_decode($episode->data),'0.description');
                         $article->body     = $episode->body;
                         $article->user_id  = random_int(1,100);
                         $article->status   = $diagram->status;
                         $article->type     = 'diagrams';
                         $article->cover_path = str_replace('http','https',data_get(json_decode($episode->data),'0.image'));
                         $article->created_at = $episode->updated_at;
                         $article->updated_at = $episode->updated_at;
                         $article->saveDataOnly();
                         $articleIds[$article->id] = [
                             'submit' => 1,
                         ];
                     }
                     $category->articles()->syncWithoutDetaching($articleIds);
                     $category->count = count($articleIds);
                     $category->saveDataOnly(['timestamps'=>false]);
                     DB::connection('neihan_sites')->commit();
                     $this->info($diagram->title);
                 } catch (\Exception $e){
                     $this->info($diagram->id);
                     $this->info($e->getMessage());
                     DB::connection('neihan_sites')->rollBack();
                 }
             }
        });
    }
}
