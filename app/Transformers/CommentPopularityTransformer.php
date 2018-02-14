<?php

namespace App\Transformers;

use App\CommentPopularity;
use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;


class CommentPopularityTransformer extends TransformerAbstract
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
    public function transform(CommentPopularity $comment)
    {
      return [
          'favorite_count' => $comment->favorite_count,
          'comment_count' => $comment->comment_count,
          'like_count' => $comment->like_count,
      ];
    }
}
