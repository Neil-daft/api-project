<?php

namespace App\Transformers;

use App\Entity\EndUser;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(EndUser $endUser)
    {
        return [
            'id' => (int)$endUser->getId(),
            'first_name' => $endUser->getFirstName(),
            'last_name' => $endUser->getLastName(),
            'email' => $endUser->getEmail(),
            'phone' => $endUser->getPhoneNumber(),
            'links' => [
                'self' => '/end/user/' . $endUser->getFirstName()
            ]
        ];
    }

}