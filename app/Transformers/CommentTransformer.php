<?php

namespace App\Transformers;

use App\Comment;
use Carbon\Carbon;
use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class CommentTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = ['user','popularity'];

    /**
     * Transform object into a generic array
     *
     * @var $resource
     * @return array
     */
    public function transform(Comment $comment)
    {
        return [
            'id' => $comment->id,
            'post_id' => $comment->post_id,
            'user_id' => $comment->user_id,
            'parent_id' => $comment->parent_id,
            'level' => $comment->level,
            'body' => $comment->body,
            'created_at' => Carbon::parse($comment->created_at)->format("Y-m-d h:i:s"),
        ];
    }

    public function includePopularity(Comment $comment)
    {
        return $this->item($comment->getPopularity(),new CommentPopularityTransformer());
    }

    public function includeUser(Comment $comment)
    {
        return $this->item($comment->getUser(), new UserOtherTransformer());
    }
}
