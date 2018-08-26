<?php

namespace App;

use App\Model;
use App\Comment;
use App\User;
use App\Events\CommentWasCreated;

class Comment extends Model
{
    protected $touches = ['commentable'];

    public $fillable = [
        'user_id',
        'body',
        'comment_id',
        'commentable_id',
        'commentable_type',
        'lou'
    ];

    public function commented()
    {
        return $this->belongsTo(\App\Comment::class);
    }

    public function replyComments()
    {
        return $this->hasMany(\App\Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class);
    }

    public function commentable()
    {
        return $this->morphTo();
    }

    public function likes(){ 
        return $this->morphMany(\App\Like::class, 'liked');
    }

    /* --------------------------------------------------------------------- */
    /* ------------------------------- service ----------------------------- */
    /* --------------------------------------------------------------------- */
     /**
     * @Desc     保存评论
     * @Author   czg
     * @DateTime 2018-07-11
     * @param    [type]     $input [description]
     * @return   [type]            [description]
     */
    public function store( $input )
    {
        $input['user_id'] = getUser()->id;
        
        //判断是直接回复文章
        if (isset($input['comment_id']) && !empty($input['comment_id'])) {
            $input['lou'] = 0;
            //拿到楼中楼的父评论,顶楼则不变
            $comment  = Comment::findOrFail($input['comment_id']);
            if(!empty($comment->comment_id)){//不为空是楼中楼
                $input['comment_id'] = $comment->comment_id;
            }
        } else {
            $input['lou'] = Comment::where('commentable_id', $input['commentable_id'])
                ->where('comment_id', null)
                ->where('commentable_type', get_polymorph_types($input['commentable_type']))
                ->count() + 1;
        }
        //防止XSS
        $input['body'] = htmlentities($input['body']);
        $this->fill($input);
        $this->save();

        event(new CommentWasCreated($this)); 

        //新评论，一起给前端返回 空的子评论 和 子评论的用户信息结构，方便前端直接回复刚发布的新评论
        $this->load('user', 'replyComments.user');

        return $this;
    }
    /**
     * @Desc     为手机端格式化commentBody
     * @Author   czg
     * @DateTime 2018-07-10
     * @return   [type]     [description]
     */
    public function wrapperBodyToMobile(){
        //以后可能#专题,先占个坑，方便以后扩展
        $commentBody = $this->body;
        $pattern = "/<a.*?href=['|\"].*?\/(.*?)\/(.*?)['|\"].*?>@(.*?)<\/a>/ui"; 
        preg_match_all($pattern, $commentBody, $matches);
        //记录了通知的对象
        $notify_objs = [];
        for ($i=0; $i < count(end($matches)); $i++) {
            $type = [];
            $type['type']  = $matches[1][$i];
            $type['id']    = $matches[2][$i];
            $type['name']  = $matches[3][$i];
            $notify_objs[] = $type;
        }
        
        $commentBody = str_replace( 
            head($matches), 
            "<flag></flag>", 
            $commentBody
        );
        $flag_array = explode("<flag>",$commentBody);
        $positions;
        for ($i=0; $i < count($flag_array); $i++) { 
            if( str_contains($flag_array[$i], "</flag>") ){
                $text = substr($flag_array[$i], 7);
                if(!is_null($text)){
                    $flag_array[$i] = ['</flag>',$text];
                }
            }
        }
        $flag_array = array_flatten($flag_array);
        for ($i=0; $i < count($flag_array); $i++) { 
            if( $flag_array[$i] == "</flag>" ){
                $positions[] = $i;
            }
        }
        if(!empty($positions)){
            for($i=0; $i < count($positions); $i++){
                $position = $positions[$i];
                $flag_array[$position] = $notify_objs[$i];
            }
        }
        $flag_array = array_filter($flag_array, function($v){
            return $v != "";
        });
        $json = json_encode(array_values($flag_array),JSON_UNESCAPED_UNICODE);
        return $json;
    }

}
