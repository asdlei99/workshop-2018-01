<?php

namespace App\Transformers;

use App\Post;
use Illuminate\Support\Carbon;
use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class PostSimpleDataTransformer extends TransformerAbstract
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
    protected $defaultIncludes = ['archive','user','popularity'];

    /**
     * 与PostTransformer的区别除了body和created_at字段，
     *
     * @var $resource
     * @return array
     */
    public function transform(Post $post)
    {
        $data = [
            'id' => $post->id,
            'title' => $post->title,
            'description' => $post->description,
            'anonymous' => $post->anoymous,
            'views' => $post->views,
//            'created_at' => Carbon::parse($post->created_at)->format("Y-m-d h:i:s") ,
            'updated_at' => Carbon::parse($post->updated_at)->format("Y-m-d h:i:s") ,
        ];

        if($post->index !== null){
            $data['index'] = $post->index;
        }

        return $data;
    }

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
    }

    public function includePopularity(Post $post)
    {
        return $this->item($post->getPopularity(),new PostPopularityTransformer());
    }
}
