<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class StateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   

        $builder
            ->setAction($options['action'])
            ->add('name', TextType::class, array('label' => 'Nome do estado', 'attr' => array('class' => 'form-control')))
            ->add('code', TextType::class, array('label' => 'Sigla do estado', 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 20px;'),  'label' => 'Salvar estado'));
    }

}