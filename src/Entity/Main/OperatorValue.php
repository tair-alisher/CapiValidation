<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Table(name="public.operator_value")
 * @ORM\Entity
 */
class OperatorValue
{
    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\CompareOperator", mappedBy="operatorValue")
     */
    private $compareOperators;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\LogicOperator", mappedBy="operatorValue")
     */
    private $logicOperators;

    /**
     * Set $id
     * Set $compareOperators
     * Set $logicOperators
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->compareOperators = new ArrayCollection();
        $this->logicOperators = new ArrayCollection();
    }

    /**
     * Get $id
     *
     * @return guid
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set $title
     *
     * @param string
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get $title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set $name
     *
     * @param string
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get $name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get $compareOperators
     *
     * @return Collection|CompareOperator[]
     */
    public function getCompareOperators(): Collection
    {
        return $this->compareOperators;
    }

    /**
     * Get $logicOperators
     *
     * @return Collection|LogicOperator[]
     */
    public function getLocigOperators(): Collection
    {
        return $this->logicOperators;
    }
}