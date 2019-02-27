<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class CreateValidationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $getter = $options['getter'];
        $inputValueTypes = $getter->getInputValueTypes();
        $compareOperators = $getter->getCompareOperators();
        $comparedValueTypes = $getter->getComparedValueTypes();
        $answerIndicators = $getter->getAnswerIndicators();

        $questionnaireRepository = $options['questionnaire_repository'];
        $questionnaires = $questionnaireRepository->getTitleIdArray();

        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Наименование контроля*',
                'attr' => ['placeholder' => 'пример: hhCode: длина значения равна шести символам']
            ])
            // проверяемый ответ
            ->add('answerCode', TextType::class, [
                'required' => true,
                'label' => 'Код вопроса',
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
                'label' => 'Оператор сравнения'
            ])
            ->add('comparedValue', TextType::class, [
                'label' => 'Значение',
                'attr' => ['placeholder' => 'пример: 10']
            ])
            ->add('comparedValueType', ChoiceType::class, [
                'choices' => $comparedValueTypes,
                'label' => 'Тип'
            ])
            ->add('comparedValueInSameSection', CheckboxType::class, [
                'label' => 'Находится в той же секции, что и проверяемый ответ?',
                'required' => false,
                'value' => 1
            ])
            // связынй ответ
            ->add('relAnswerCode', TextType::class, [
                'label' => 'Код вопроса',
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
            ->add('inSameSection', CheckboxType::class, [
                'label' => 'Связный ответ находится в той же секции, что и проверяемый ответ?',
                'required' => false,
                'value' => 1
            ])
            // опросник
            ->add('questionnaireId', ChoiceType::class, [
                'choices' => $questionnaires,
                'label' => 'Опросник*',
                'attr' => ['class' => 'questionnaire-id']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(['getter', 'questionnaire_repository']);
    }
}