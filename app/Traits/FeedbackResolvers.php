<?php

namespace App\Traits;

use App\Feedback;
use App\Image;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

trait FeedbackResolvers
{
    public function resolveAllFeedbacks($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        return Feedback::orderBy('top_at', 'desc')->orderBy('rank', 'desc');
    }

    public function resolveCreateFeedback($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $user    = getUser();
        $contact = array_get($args, "contact");
        if (BadWordUtils::check($args['content'])) {
            throw new GQLException('发布的反馈中含有包含非法内容,请删除后再试!');
        }
        $feedback = Feedback::firstOrCreate([
            'user_id' => $user->id,
            'content' => $args['content'],
            'contact' => $contact,
        ]);

        if (!empty($args['images'])) {
            foreach ($args['images'] as $image) {
                $image = Image::saveImage($image);
                $feedback->images()->attach($image->id);
            }
        }

        if (!empty($args['image_urls']) && is_array($args['image_urls'])) {
            $image_ids = array_map(function ($url) {
                return intval(pathinfo($url)['filename']);
            }, $args['image_urls']);
            $feedback->images()->sync($image_ids);
            $feedback->save();
        }

        return $feedback;
    }
}
