<?php

namespace App\Transformers;

use App\PostPopularity;
use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class PostPopularityTransformer extends TransformerAbstract
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
    protected $defaultIncludes = [];

    /**
     * Transform object into a generic array
     *
     * @var $resource
     * @return array
     */
    public function transform(PostPopularity $popularity)
    {
        return [
            'like_count' => $popularity->like_count,
            'comment_count' => $popularity->comment_count,
            'favorite_count' => $popularity->favorite_count,
            'view_count' => $popularity->view_count,
        ];
    }
}
