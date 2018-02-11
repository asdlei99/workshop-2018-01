<?php

namespace App\Transformers;

use League\Fractal;
use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use App\User;


class UserSelfTransformer extends TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = ['post'];

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
    public function transform(User $user)
    {
        $data = [
            'id' => $user->id,
            'username' => $user->username,
            'nickname' => $user->nickname,
            'signature' => $user->signature,
            'head_img' => $user->head_img,
//            'user_group' => $user->user_group,
            'email' => $user->email,
            'phone' => $user->phone,
            'qq' => $user->qq,
            'email_access' => $user->email_access,
            'phone_access' => $user->phone_access,
            'qq_access' => $user->qq_access,
        ];

        return $data;
    }

}
