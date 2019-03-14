<?php

namespace App\Form;

use App\Repository\Remote\QuestionnaireRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ValidateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** var \App\Repository\Remove\QuestionnaireRepository $questionnaireRepository */
        $questionnaireRepository = $options['questionnaire_repository'];
        $questionnaires = $questionnaireRepository->getTitleIdArray();

        $builder
            ->add('questionnaire', ChoiceType::class, [
                'choices' => $questionnaires,
                'label' => 'Форма'
            ]);
//            ->add('quarter', NumberType::class, [
//                'required' => false,
//                'label' => 'Квартал',
//                'attr' => ['placeholder' => 'квартал']
//            ])
//            ->add('month', NumberType::class, [
//                'required' => false,
//                'label' => 'Месяц',
//                'attr' => ['placeholder' => 'месяц']
//            ]);
//            ->add('area', TextType::class, [
//                'required' => false,
//                'label' => 'Область',
//                'attr' => ['placeholder' => 'область']
//            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('questionnaire_repository');
    }
}