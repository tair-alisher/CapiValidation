<?php

namespace App\Service;

use App\Entity\Main\Validation;
use App\Entity\Main\LogicOperator;
use App\Entity\Main\InputValueType;
use App\Entity\Main\AnswerIndicator;
use App\Entity\Main\CompareOperator;
use App\Entity\Main\ComparedValueType;
use Doctrine\ORM\EntityManagerInterface;

class Getter
{
    private $em;

    /**
     * @param \Doctrine\ORM\EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Returns InputValueType repository
     */
    public function inputValueTypeRepo()
    {
        return $this->em->getRepository(InputValueType::class);
    }

    /**
     * Returns AnswerIndicator repository
     */
    public function answerIndicatorRepo()
    {
        return $this->em->getRepository(AnswerIndicator::class);
    }

    /**
     * Returns ComparedValueType repository
     */
    public function comparedValueTypeRepo()
    {
        return $this->em->getRepository(ComparedValueType::class);
    }

    /**
     * Returns CompareOperator repository
     */
    public function compareOperatorRepo()
    {
        return $this->em->getRepository(CompareOperator::class);
    }

    /**
     * Returns Validation repository
     */
    public function validationRepo()
    {
        return $this->em->getRepository(Validation::class);
    }

    /**
     * Returns LogicOperator repository
     */
    public function logicOperatorRepo()
    {
        return $this->em->getRepository(LogicOperator::class);
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

        ksort($titleIdArray);

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

        ksort($titleIdArray);

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

        ksort($titleIdArray);

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

        ksort($titleIdArray);

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