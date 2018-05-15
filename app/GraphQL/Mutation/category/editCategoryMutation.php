<?php
namespace App\GraphQL\Mutation\category;

use App\Category;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Overtrue\Pinyin\Pinyin;

class editCategoryMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'editCategoryMutation',
        'description' => 'edit a Category ',
    ];

    public function type()
    {
        return GraphQL::type('Category');
    }

    public function args()
    {
        return [
            'id'           => ['name' => 'id', 'type' => Type::int()],
            'name'         => ['name' => 'name', 'type' => Type::string()],
            'logo'         => ['name' => 'logo', 'type' => Type::string()],
            'description'  => ['name' => 'description', 'type' => Type::string()],
            'allow_submit' => ['name' => 'allow_submit', 'type' => Type::boolean()],
            'need_approve' => ['name' => 'need_approve', 'type' => Type::boolean()],
        ];
    }

    public function rules()
    {
        return [
            'id'           => ['required'],
            'name'         => ['required'],
            'logo'         => ['required'],
            'description'  => ['required'],
            'allow_submit' => ['required'],
            'need_approve' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        $user = getUser();

        $category       = Category::findOrFail($args['id']);
        $category->name = $args['name'];

        $pinyin  = new Pinyin();
        $name_en = @$pinyin->sentence($category->name);
        if ($name_en) {
            $category->name_en = preg_replace('# #', '', trim($name_en));
        } else {
            $category->name_en = "c" . (Category::max('id') + 1);
        }
        $category->logo           = parse_url($args['logo'], PHP_URL_PATH);
        $category->description    = $args['description'];
        $category->user_id        = $user->id;
        $category->status         = $args['allow_submit'];
        $category->request_status = $args['need_approve'];
        $category->save();

        return $category;
    }
}
