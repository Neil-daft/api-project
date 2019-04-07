<?php

namespace App\Transformers;

use App\Entity\Job;
use App\Entity\User;
use League\Fractal\TransformerAbstract;

class JsonGardenerTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'jobs'
    ];

    /**
     * @param \App\Entity\User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => (int)$user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'links' => [
                'self' => '/users/' . $user->getId()
            ]
        ];
    }

    public function includeJobs(User $user)
    {
        $job = $user->getJobs();
        if (empty($job)) {
            $job = new Job();
        }
        return $this->collection($job, new JsonJobTransformer(), 'jobs');
    }
}