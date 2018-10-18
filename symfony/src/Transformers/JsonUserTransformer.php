<?php

namespace App\Transformers;

use App\Entity\EndUser;
use App\Entity\Job;
use League\Fractal\TransformerAbstract;

class JsonUserTransformer extends TransformerAbstract
{
    /**
     * List of resources to include automatically.
     * @var array
     */
    protected $defaultIncludes = [
        'job'
    ];

    public function transform(EndUser $endUser)
    {
        return [
            'id' => (int)$endUser->getId(),
            'first_name' => $endUser->getFirstName(),
            'last_name' => $endUser->getLastName(),
            'email' => $endUser->getEmail(),
            'phone' => $endUser->getPhoneNumber(),
            'links' => [
                'self' => '/users/' . $endUser->getId()
            ]
        ];
    }

    public function includeJob(EndUser $endUser)
    {
        $job = $endUser->getJob();
        if (is_null($job)) {
            $job = new Job();
        }
        return $this->item($job, new JobTransformer(), 'job');
    }

}