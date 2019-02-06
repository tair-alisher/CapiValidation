<?php

namespace App\Form;

use App\Entity\Main\Validation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CreateValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** var \App\Service\Getter $validator */
        $getter = $options['getter'];
        $inputValueTypes = $getter->getInputValueTypes();
        $compareOperators = $getter->getCompareOperators();
        $comparedValueTypes = $getter->getComparedValueTypes();
        $answerIndicators = $getter->getAnswerIndicators();

        $questionnaireRepository = $options['questionnaire_repository'];
        $items = $questionnaireRepository->getAllOrderedByTitle();
        $questionnaires = [];

        foreach ($items as $item) {
            $questionnaires[$item->getTitle()] = $item->getId();
        }

        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Наименование контроля*',
                'attr' => ['placeholder' => 'пример: hhCode: длина значения равна шести символам']
            ])
            // проверяемый ответ
            ->add('answerCode', TextType::class, [
                'required' => true,
                'label' => 'Код ответа',
                'attr' => ['placeholder' => 'пример: hhCode']
            ])
            ->add('answerType', ChoiceType::class, [
                'choices' => $inputValueTypes,
                'label' => 'Тип'
            ])
            ->add('answerIndicator', ChoiceType::class, [
                'choices' => $answerIndicators,
                'label' => 'Атрибут'
            ])
            // сравниваемые значения
            ->add('compareOperator', ChoiceType::class, [
                'choices' => $compareOperators,
                'label' => 'Оператор сравнения',
                'attr' => ['class' => 'compared-value-operator-id']
            ])
            ->add('comparedValues', TextType::class, [
                'label' => 'Значение',
                'attr' => [
                    'placeholder' => 'пример: 10',
                    'class' => 'compared-value-input'
                ]
            ])
            ->add('comparedValueTypes', ChoiceType::class, [
                'choices' => $comparedValueTypes,
                'label' => 'Тип',
                'attr' => ['class' => 'compared-value-type-id']
            ])
            // связынй ответ
            ->add('relAnswerCode', TextType::class, [
                'label' => 'Код ответа',
                'required' => false,
                'attr' => ['placeholder' => 'пример: resultB']
            ])
            ->add('relAnswerCompareOperator', ChoiceType::class, [
                'choices' => $compareOperators,
                'label' => 'Оператор сравнения'
            ])
            ->add('relAnswerValue', TextType::class, [
                'label' => 'Значение',
                'required' => false,
                'attr' => ['placeholder' => 'пример: 10']
            ])
            ->add('relAnswerType', ChoiceType::class, [
                'choices' => $comparedValueTypes,
                'label' => 'Тип'
            ])
            // опросник
            ->add('questionnaireId', ChoiceType::class, [
                'choices' => $questionnaires,
                'label' => 'Опросник*'
            ])
            ->add('create', SubmitType::class, [
                'label' => 'Сохранить',
                'attr' => ['class' => 'btn-success pull-right']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['getter', 'questionnaire_repository']);
    }
}