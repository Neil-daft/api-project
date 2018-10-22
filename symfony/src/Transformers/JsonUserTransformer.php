<?php

namespace App\Transformers;

use App\Entity\EndUser;
use App\Entity\Job;
use App\Entity\User;
use League\Fractal\TransformerAbstract;

class JsonUserTransformer extends TransformerAbstract
{
    /**
     * List of resources to include automatically.
     * @var array
     */
    protected $availableIncludes = [
        'job'
    ];

    public function transform(User $user)
    {
        return [
            'id' => (int)$user->getId(),
            'email' => $user->getEmail(),
            'links' => [
                'self' => '/users/' . $user->getId()
            ]
        ];
    }

    public function includeJob(User $user)
    {
        $job = $user->getJob();
        if (is_null($job)) {
            $job = new Job();
        }
        return $this->collection($job, new JobTransformer(), 'job');
    }

}