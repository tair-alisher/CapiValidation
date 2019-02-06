<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Main\InputValueType;
use App\Entity\Main\CompareOperator;
use App\Entity\Main\ComparedValueType;
use App\Entity\Main\AnswerIndicator;
use App\Entity\Main\LogicOperator;

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
     * Returns associative array [InputValue.title => InputValueType.id]
     *
     * @return array
     */
    public function getInputValueTypes()
    {
        $items = $this->em->getRepository(InputValueType::class)->findAll();
        $titleIdArray = array();

        foreach ($items as $item) {
            $titleIdArray[$item->getValueType()->getTitle()] = $item->getId();
        }

        return $titleIdArray;
    }

    /**
     * Returns associative array [OperatorValue.title => CompareOperator.id]
     *
     * @return array
     */
    public function getCompareOperators()
    {
        $items = $this->em->getRepository(CompareOperator::class)->findAll();
        $titleIdArray = array();

        foreach ($items as $item) {
            $titleIdArray[$item->getOperatorValue()->getTitle()] = $item->getId();
        }

        return $titleIdArray;
    }

    /**
     * Returns associative array [ValueType.title => ComparedValueType.id]
     *
     * @return array
     */
    public function getComparedValueTypes()
    {
        $items = $this->em->getRepository(ComparedValueType::class)->findAll();
        $titleIdArray = array();

        foreach ($items as $item) {
            $titleIdArray[$item->getValueType()->getTitle()] = $item->getId();
        }

        return $titleIdArray;
    }

    /**
     * Returns associative array [AnswerIndicator.title => AnswerIndicator.id]
     *
     * @return array
     */
    public function getAnswerIndicators()
    {
        $items = $this->em->getRepository(AnswerIndicator::class)->findAll();
        $titleIdArray = array();

        foreach ($items as $item) {
            $titleIdArray[$item->getTitle()] = $item->getId();
        }

        return $titleIdArray;
    }

    public function getLogicOperators()
    {
        $items = $this->em->getRepository(LogicOperator::class)->findAll();
        $titleIdArray = array();

        foreach ($items as $item) {
            $titleIdArray[$item->getOperatorValue()->getTitle()] = $item->getId();
        }

        return $titleIdArray;
    }
}