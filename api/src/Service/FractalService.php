<?php

namespace App\Service;

use League\Fractal\Manager;
use League\Fractal\Serializer\JsonApiSerializer;

class FractalService
{
    /** @var Manager  */
    private $manager;

    public function __construct()
    {
        $this->manager = new Manager();
    }

    public function getFractal(): Manager
    {
        $this->manager->setSerializer(new JsonApiSerializer('http://symfony.local'));;

        return $this->manager;
    }
}