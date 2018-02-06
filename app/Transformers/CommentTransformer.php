<?php

namespace App\Transformers;

use App\Comment;
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
    protected $availableIncludes = ['post','user','comment'];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [];

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
            'created_at' => $comment->created_at,
        ];
    }

    public function includeChildren(Comment $comment)
    {
        if($comment->level > 1){
            return null;
        }else{
            return $this->collection($comment->children(),new CommentTransformer());
        }
    }
}
