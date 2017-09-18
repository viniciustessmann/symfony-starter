<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   

        $builder
            ->setAction($options['action'])
            ->add('name', TextType::class, array('label' => 'Nome', 'attr' => array('class' => 'form-control')))
            ->add('email', TextType::class, array('label' => 'E-mail', 'attr' => array('class' => 'form-control')))
            // ->add('role', ChoiceType::class, array('label' => 'Você é um?', 'attr' =>  array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 20px;'),  'label' => 'Salvar'));
    }

}