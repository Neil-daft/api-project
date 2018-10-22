<?php

namespace App\Transformers;

use App\Entity\Job;
use App\Entity\User;
use League\Fractal\TransformerAbstract;

class JobTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'user'
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

    public function includeUser(Job $job)
    {
        $user = $job->getUser();

        if (is_null($user)) {
            $user = new User();
        }

        return $this->item($user, new JsonUserTransformer(), 'user');
    }
}
