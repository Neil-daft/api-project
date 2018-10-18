<?php

namespace App\Transformers;

use App\Entity\Job;
use League\Fractal\TransformerAbstract;

class JobTransformer extends TransformerAbstract
{
    public function transform(Job $job)
    {
        return [
            'id' => (int)$job->getId(),
            'title' => $job->getTitle(),
            'description' => $job->getDescription(),
            'created_on' => $job->getCreatedOn(),
            'owner' => $job->getOwner()
        ];
    }
}
