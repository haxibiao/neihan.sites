<?php
namespace App\GraphQL\Mutation\category;

use App\Category;
use Folklore\GraphQL\Support\Mutation;
use GraphQL;
use GraphQL\Type\Definition\Type;
use Overtrue\Pinyin\Pinyin;

class createCategoryMutation extends Mutation
{
    protected $attributes = [
        'name'        => 'createCategoryMutation',
        'description' => 'create a Category ',
    ];

    public function type()
    {
        return GraphQL::type('Category');
    }

    public function args()
    {
        return [
            'name'         => ['name' => 'name', 'type' => Type::string()],
            'logo'         => ['name' => 'logo', 'type' => Type::string()],
            'description'  => ['name' => 'description', 'type' => Type::string()],
            'admin_uids'   => ['name' => 'admin_uids', 'type' => Type::string()],
            'allow_submit' => ['name' => 'allow_submit', 'type' => Type::boolean()],
            'need_approve' => ['name' => 'need_approve', 'type' => Type::boolean()],
        ];
    }

    public function rules()
    {
        return [
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

        $category = Category::firstOrNew([
            'name' => $args['name'],
        ]);
        $pinyin  = new Pinyin();
        $name_en = @$pinyin->sentence($category->name);
        if ($name_en) {
            $category->name_en = preg_replace('# #', '', trim($name_en));
        } else {
            $category->name_en = "c" . (Category::max('id') + 1);
        }
        $category->status         = $args['allow_submit'];
        $category->request_status = $args['need_approve'];
        $category->logo           = parse_url($args['logo'], PHP_URL_PATH);
        $category->description    = $args['description'];
        $category->user_id        = $user->id;
        $category->type           = "article";
        $category->save();

        if (isset($args['admin_uids'])) {
            $admin_uids = explode(",", $args['admin_uids']);
            $data       = [
                $user->id => ['is_admin' => 1],
            ];
            foreach ($admin_uids as $uid) {
                $data[$uid] = ['is_admin' => 1];
            }
            $category->admins()->sync($data);
        }

        return $category;
    }
}
