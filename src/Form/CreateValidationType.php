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

        $questionnaireRepository = $options['questionnaire_repository'];
        $items = $questionnaireRepository->findAll();
        $questionnaires = [];

        foreach ($items as $item) {
            $questionnaires[$item->getTitle()] = $item->getId();
        }

        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Наименоваине контроля',
                'attr' => ['placeholder' => 'пример: hhCode: для на значения равна шести символам']
            ])

            ->add('answerCode', TextType::class, [
                'required' => true,
                'label' => 'Проверяемое значение',
                'attr' => ['placeholder' => 'пример: hhCode']
            ])
            ->add('answerType', TextType::class, [
                'label' => 'Значение условия',
                'attr' => ['placeholder' => 'пример: 10|null|10,20,30']
            ])
            ->add('answerIndicator', ChoiceType::class, [
                'choices' => $inputValueTypes,
                'label' => 'Тип значения'
            ])

            ->add('compareOperator', ChoiceType::class, [
                'choices' => $compareOperators,
                'label' => 'Оператор сравнения',
            ])
            ->add('comparedValues', TextType::class, [
                'label' => 'Сравниваемое значение',
                'attr' => ['placeholder' => 'пример: 10']
            ])
            ->add('comparedValueTypes', ChoiceType::class, [
                'choices' => $comparedValueTypes,
                'label' => 'Тип'
            ])

            ->add('relAnswerCode', TextType::class, [
                'label' => 'Зависит от ответа (код)',
                'attr' => ['placeholder' => 'пример: resultB']
            ])
            ->add('relAnswerCompareOperator', ChoiceType::class, [
                'choices' => $compareOperators,
                'label' => 'Оператор сравнения'
            ])
            ->add('relAnswerValue', TextType::class, [
                'label' => 'Значение',
                'attr' => ['placeholder' => 'пример: 10']
            ])
            ->add('relAnswerType', ChoiceType::class, [
                'choices' => $comparedValueTypes,
                'label' => 'Тип'
            ])

            ->add('questionnaireId', ChoiceType::class, [
                'choices' => $questionnaires,
                'label' => 'Опросник'
            ])
            ->add('create', SubmitType::class, [
                'label' => 'Добавить',
                'attr' => ['class' => 'btn-primary pull-right']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => Validation::class,
            ])
            ->setRequired(['getter', 'questionnaire_repository']);
    }
}