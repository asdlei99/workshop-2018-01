<?php

namespace App\Transformers;

use App\CommentLikeMessageControl;
use App\CommentMessageControl;
use App\PostLikeMessageControl;
use Carbon\Carbon;
use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class MessageControlTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = ['comment','post','comment_like','post_like'];

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
    public function transform($resource)
    {
        $data = [
            'id' => $resource->id,
            'has_read' => $resource->has_read,
            'created_at' => Carbon::parse($resource->created_at)->format("Y-m-d h:i:s"),
        ];

        if(is_a($resource,CommentMessageControl::class)){
            $data['comment_id'] = $resource->comment_id;
        }elseif(is_a($resource,PostLikeMessageControl::class)){
            $data['like_type'] = 'post';
            $data['post_like_id'] = $resource->like_id;
        }elseif(is_a($resource,CommentLikeMessageControl::class)){
            $data['like_type'] = 'comment';
            $data['comment_like_id'] = $resource->like_id;
        }
        return $data;
    }

    public function includeComment($resource)
    {
        return $this->item($resource->getComment(),function($comment){
            return [
                'id' => $comment->id,
                'body' => $comment->body,
            ];
        });
    }

    public function includePost($resource)
    {
        if(is_a($resource,PostLikeMessageControl::class)){
            return $this->item($resource->getPost(),function($post){
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'description' => $post->description,
                ];
            });
        }
    }
}
