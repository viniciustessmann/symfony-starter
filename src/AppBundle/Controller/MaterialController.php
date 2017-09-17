<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Material;
use AppBundle\Service\UserService;
use AppBundle\Service\MailerService;
use AppBundle\Service\MaterialService;
use AppBundle\Service\CourseService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\MaterialType;
use AppBundle\Service\UploadService;

class MaterialController extends Controller
{
	/**
    *
    * @Route("/create_material_form", name="create_material_form")
    */
    public function createFormAction() 
    {   
    	$material = new Material();
    	$form = $this->createForm(MaterialType::class, $material, ['action' => $this->generateUrl('create_material')]);

        return $this->render('material/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }	

    /**
    *
    * @Route("/create_material", name="create_material")
    */
    public function createMaterial(Request $request)
    {   
        $file = $request->files->get('material')['file'];
        $data = $request->request->get('material');

        $material = new Material();

        $material->setName($data['name']);
        $material->setDescription($data['description']);
        $material->setCourse($this->get(CourseService::class)->getCourseById(1));
        $material->setFile($file);

        
        UploadService::uploadFile($material);

        $response = $this->get(MaterialService::class)->addMaterial($material);
        
        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        echo 'Material cadastrado com sucesso.';
        die;
    }

}
