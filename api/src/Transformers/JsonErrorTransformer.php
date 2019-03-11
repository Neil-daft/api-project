<?php

namespace App\Transformers;

use App\Entity\NullEntity;
use League\Fractal\TransformerAbstract;

class JsonErrorTransformer extends TransformerAbstract
{
    public function transform(NullEntity $null)
    {
        return [
            'id' => '404 not found',
            'title' => $null->getTitle(),
            'description' => $null->getDescription()
        ];
    }
}