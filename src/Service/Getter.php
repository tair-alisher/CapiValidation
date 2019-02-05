<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Main\InputValueType;
use App\Entity\Main\CompareOperator;
use App\Entity\Main\ComparedValueType;

class Getter
{
    private $em;

    /**
     * @param Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Returns associative array [InputValue.value => InputValueType.id]
     *
     * @return array
     */
    public function getInputValueTypes()
    {
        $items = $this->em->getRepository(InputValueType::class)->findAll();
        $valueIdArray = array();

        foreach ($items as $item) {
            $valueIdArray[$item->getValueType()->getTitle()] = $item->getId();
        }

        return $valueIdArray;
    }

    /**
     * Returns associative array [OperatorValue.title => CompareOperator.id]
     *
     * @return array
     */
    public function getCompareOperators()
    {
        $items = $this->em->getRepository(CompareOperator::class)->findAll();
        $valueIdArray = array();

        foreach ($items as $item) {
            $valueIdArray[$item->getOperatorValue()->getTitle()] = $item->getId();
        }

        return $valueIdArray;
    }

    /**
     * Returns associative array [ValeuType.value => ComparedValueType.id]
     *
     * @return array
     */
    public function getComparedValueTypes()
    {
        $items = $this->em->getRepository(ComparedValueType::class)->findAll();
        $valueIdArray = array();

        foreach ($items as $item) {
            $valueIdArray[$item->getValueType()->getTitle()] = $item->getId();
        }

        return $valueIdArray;
    }
}