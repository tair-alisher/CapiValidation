<?php

namespace App\Form;

use App\Repository\Main\RestraintRepository;
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
        /** var \App\Repository\Main\RestraintRepository $restraintRepository */
        $restraintRepository = $options['restraint_repository'];
        $restraints = $restraintRepository->findAll();
        $restraintTitleIdArr = [];

        foreach ($restraints as $restraint) {
            $restraintTitleIdArr[$restraint->getTitle()] = $restraint->getId();
        }

        $questionnaireRepository = $options['questionnaire_repository'];
        $questionnaires = $questionnaireRepository->findAll();
        $questionnaireTitleIdArr = [];

        foreach ($questionnaires as $questionnaire) {
            $questionnaireTitleIdArr[$questionnaire->getTitle()] = $questionnaire->getId();
        }

        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Название првоерки',
                'attr' => ['placeholder' => 'пример: hhCode: содержит значение из диапазона значений']
            ])
            ->add('questionId', TextType::class, [
                'required' => true,
                'label' => 'Идентификатор вопроса',
                'attr' => ['placeholder' => 'пример: hhCode']
            ])
            ->add('restraintId', ChoiceType::class, [
                'choices' => $restraintTitleIdArr,
                'label' => 'Условие'
            ])
            ->add('condition', TextType::class, [
                'label' => 'Значение условия',
                'attr' => ['placeholder' => 'пример: 10|null|10,20,30']
            ])
            ->add('relatedQuestionId', TextType::class, [
                'required' => false,
                'label' => 'Код связного вопроса (если зависит от другого вопроса)',
                'attr' => ['placeholder' => 'пример: f3r1q1']
            ])
            ->add('relatedQuestionCondition', TextType::class, [
                'required' => false,
                'label' => 'Значение связного вопроса (если зависит от другого вопроса)',
                'attr' => ['placeholder' => 'пример: 1']
            ])
            ->add('questionnaireId', ChoiceType::class, [
                'choices' => $questionnaireTitleIdArr,
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
            ->setRequired(['restraint_repository', 'questionnaire_repository']);
    }
}