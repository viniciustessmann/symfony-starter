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


class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   

        $builder
            ->setAction($options['action'])
            ->add('name', TextType::class, array('label' => 'Nome da cidade', 'attr' => array('class' => 'form-control')))
            ->add('state', ChoiceType::class,  ['choices' => $options['states'], 'label' => 'Selecione o estado', 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 20px;'),  'label' => 'Salvar cidade'))
            ->getForm();
    }

    /**
    * {@inheritdoc}
    */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\City'
        ))->setRequired('states');
    }
}