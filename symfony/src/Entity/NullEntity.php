<?php

namespace App\Entity;

class NullEntity
{
    private $id;
    private $title = 'Null';
    private $description = 'No resource found';

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }


}