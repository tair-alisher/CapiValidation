<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mappin as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collection\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.compared_value")
 */
class ComparedValue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\Validation", inversedBy="comparedValues")
     */
    private $validation;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\ComparedValueType", inversedBy="comparedValues")
     */
    private $valueType;

    /**
     * @ORM\Column(type="string", name="c_value")
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Main\CompareOperator", inversedBy="comparedValues")
     */
    private $compareOperator;
}