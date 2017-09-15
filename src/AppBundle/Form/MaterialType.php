<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Vich\UploaderBundle\Form\Type\VichFileType;

class MaterialType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
        $builder
            ->setAction($options['action'])
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('file', FileType::class)
            ->add('save', SubmitType::class, array('label' => 'Criar material'));
    }

}