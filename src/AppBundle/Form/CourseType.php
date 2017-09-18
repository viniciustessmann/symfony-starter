<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   

        $builder
            ->setAction($options['action'])
            ->add('name', TextType::class, array('label' => 'Nome do curso', 'attr' => array('class' => 'form-control')))
            ->add('description', TextType::class, array('label' => 'Descrição do curso', 'attr' => array('class' => 'form-control')))
            ->add('starter', DateType::class,  ['label' => 'Data de início do curso', 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 20px;'),  'label' => 'Salvar treinamento'))
            ->getForm();
    }

}