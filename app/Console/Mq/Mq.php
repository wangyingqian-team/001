<?php
namespace App\Console\Mq;

class Mq
{
    protected $id;

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}


$a = new Mq();

$b = clone $a;

$a->setId('a');

echo $b->getId();