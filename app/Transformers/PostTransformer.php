<?php

namespace App\Transformers;

use Illuminate\Support\Carbon;
use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use App\Post;


class PostTransformer extends TransformerAbstract
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
    protected $defaultIncludes = ['archive','user'];
    //TODO 类别

    /**
     * Transform object into a generic array
     *
     * @var $resource
     * @return array
     */
    public function transform(Post $post)
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'body' => $post->body,
            'anonymous' => $post->anoymous,
            'views' => $post->views,
            'date' => Carbon::parse($post->created_at)->format("Y M d H:m:s") ,
        ];
    }

    /**
     * @param Post $post
     * @return Item|null
     */
    public function includeUser(Post $post)
    {
        if($post->anonymous){   //判断是否匿名
            return null;
        }else{
            return $this->item($post->getUser(),new UserOtherTransformer());
        }
    }

    public function includeArchive(Post $post)
    {
        return $this->collection($post->getArchive(),new ArchiveTransformer());
        return $this->item($post->getArchive(),new ArchiveTransformer());
    }
}
