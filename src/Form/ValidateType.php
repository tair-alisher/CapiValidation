<?php

namespace App\Form;

use App\Repository\Remote\QuestionnaireRepository;
use App\Entity\Main\Validation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ValidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** var \App\Repository\Remove\QuestionnaireRepository $questionnareRepository */
        $questionnaireRepository = $options['questionnaire_repository'];
        $questionnaires = $questionnaireRepository->getTitleIdArray();

        $builder
            ->add('questionnaire', ChoiceType::class, [
                'choices' => $questionnaires,
                'label' => 'Форма'
            ])
            ->add('quarter', NumberType::class, [
                'required' => false,
                'label' => 'Квартал',
                'attr' => ['placeholder' => 'квартал']
            ])
            ->add('month', NumberType::class, [
                'required' => false,
                'label' => 'Месяц',
                'attr' => ['placeholder' => 'месяц']
            ])
            ->add('area', TextType::class, [
                'required' => false,
                'label' => 'Область',
                'attr' => ['placeholder' => 'область']
            ])
            ->add('validate', SubmitType::class, [
                'label' => 'Запустить',
                'attr' => ['class' => 'btn-primary pull-right']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('questionnaire_repository');
    }
}