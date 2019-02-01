<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/** @ORM\Table(name="public.value_type") */
class ValueType
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
     * @ORM\OneToMany(targetEntity="App\Entity\Main\ComparedValueType", mappedBy="valueType")
     */
    private $comparedValueTypes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\InputValueType", mappedBy="valueType")
     */
    private $inputValueTypes;

    /**
     * Set $id, $comparedValueTypes, $inputValueTypes
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->comparedValueTypes = new ArrayCollection();
        $this->inputValueTypes = new ArrayCollection();
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
     * Get $comparedValueTypes
     *
     * @return Collection|CompareValueType[]
     */
    public function getComparedValueTypes(): Collection
    {
        return $this->comparedValueTypes;
    }

    /**
     * Get $inputValueTypes
     *
     * @return Collection|InputValueType[]
     */
    public function getInputValueTypes(): Collection
    {
        return $this->inputValueTypes;
    }
}