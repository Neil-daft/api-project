<?php

namespace App\Transformers;

use App\Entity\Gardener;
use App\Entity\Job;
use App\Entity\User;
use League\Fractal\TransformerAbstract;

class JsonJobTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'users',
        'gardeners'
    ];

    public function transform(Job $job)
    {
        return [
            'id' => (int)$job->getId(),
            'title' => $job->getTitle(),
            'description' => $job->getDescription(),
            'created_on' => $job->getCreatedOn(),
        ];
    }

    public function includeUsers(Job $job)
    {
        $user = $job->getUser();

        if (is_null($user)) {
            $user = new User();
        }

        return $this->item($user, new JsonUserTransformer(), 'users');
    }

    public function includeGardeners(Job $job)
    {
        $gardener = $job->getGardener();

        if (is_null($gardener)) {
            $gardener = new Gardener();
        }

        return $this->item($gardener, new JsonGardenerTransformer(), 'gardeners');
    }
}
