<?php

namespace App\Transformers;

use App\Entity\Gardener;
use App\Entity\Job;
use League\Fractal\TransformerAbstract;

class JsonGardenerTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'jobs'
    ];


    public function transform(Gardener $gardener)
    {
        return [
            'id' => (int)$gardener->getId(),
            'email' => $gardener->getEmail(),
            'roles' => $gardener->getRoles(),
            'links' => [
                'self' => '/users/' . $gardener->getId()
            ]
        ];
    }

    public function includeJobs(Gardener $gardener)
    {
        $job = $gardener->getJobs();
        if (empty($job)) {
            $job = new Job();
        }
        return $this->collection($job, new JsonJobTransformer(), 'jobs');
    }
}