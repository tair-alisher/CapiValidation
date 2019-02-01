<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Ramsey\Uuid\Uuid;

/**
 * @ORM\Table(name="public.answer_indicator")
 */
class AnswerIndicator
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
     * @ORM\Column(type="name")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Main\Validation", mappedBy="answerIndicatorType")
     */
    private $validations;

    /**
     * Set $id
     * Set $validations
     */
    public function __construct()
    {
        $this->id = Uuid::uuid4();
        $this->validations = new ArrayCollection();
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
        return $this->name = $name;
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
     * Get $validations
     *
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }
}