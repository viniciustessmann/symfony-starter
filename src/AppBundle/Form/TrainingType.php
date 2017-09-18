<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\OptionsResolver\OptionsResolver;


class TrainingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   

        $builder
            ->setAction($options['action'])
            ->add('name', TextType::class, array('label' => 'Nome do treinamento', 'attr' => array('class' => 'form-control')))
            ->add('description', TextareaType::class, array('label' => 'Descrição do treinamento', 'attr' => array('class' => 'form-control')))
            ->add('course', ChoiceType::class,  ['choices' => $options['courses'], 'label' => 'Selecione o treinamento', 'attr' => ['class' => 'form-control']])
            ->add('city', ChoiceType::class,  ['choices' => $options['cities'], 'label' => 'Selecione a cidade do treinamenot', 'attr' => ['class' => 'form-control']])
            ->add('state', ChoiceType::class,  ['choices' => $options['states'], 'label' => 'Selecione o estado do treinamento', 'attr' => ['class' => 'form-control']])
            ->add('starter', DateType::class,  ['label' => 'Data de início do treinamento', 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 20px;'),  'label' => 'Salvar treinamento'))
            ->getForm();
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
                'data_class' => 'AppBundle\Entity\Training'
            ))
            ->setRequired('courses')
            ->setRequired('states')
            ->setRequired('cities');
        
    }
}