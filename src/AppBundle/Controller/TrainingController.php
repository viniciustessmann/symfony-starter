<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;
use AppBundle\Entity\Training;
use AppBundle\Service\UserService;
use AppBundle\Service\MailerService;
use AppBundle\Service\TrainingService;
use AppBundle\Service\CourseService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class TrainingController extends Controller
{
    /**
    *
    * @Route("/list_training", name="list_training")
    */
    public function listAction() 
    {
        $trainings = $this->get(Trainingservice::class)->getAllTrainings();

        return $this->render('training/list.html.twig', array(
            'trainings' => $trainings,
        ));
    }   


    /**
    *
    * @Route("/create_training_form", name="create_training_form")
    */
    public function createFormAction(Request $request) 
    {  
        $training = new Training();

        $courses = $this->get(CourseService::class)->getAllCoursesList();


         $form = $this->createFormBuilder($training)
            ->setAction($this->generateUrl('create_training'))
            ->add('name', TextType::class, array('label' => 'Nome do curso', 'attr' => array('class' => 'form-control')))
            ->add('description', TextType::class, array('label' => 'Descrição do curso', 'attr' => array('class' => 'form-control')))
            ->add('course', ChoiceType::class,  ['choices' => $courses, 'label' => 'Selecione o curso', 'attr' => ['class' => 'form-control']])
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 20px;'),  'label' => 'Salvar treinamento'))
            ->getForm();

        return $this->render('training/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }   


    /**
    *
    * @Route("/edit_training_form/{id}", name="edit_training_form",  requirements={"id": "\d+"})
    */
    public function editFormAction($id) 
    {  
        $training = $this->get(TrainingService::class)->getTrainingById($id);

        $courses = $this->get(CoursesService::class)->getAllCoursesList();

        $form = $this->createFormBuilder($training)
            ->setAction($this->generateUrl('create_training'))
            ->add('id', HiddenType::class)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Atualizar treinamento'))
            ->getForm();

        return $this->render('training/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }   

    /**
    *
    * @Route("/delete_training/{id}", name="delete_training",  requirements={"id": "\d+"})
    */
    public function deleteFormAction($id) 
    {  
        $response = $this->get(TrainingService::class)->deleteTrainingById($id);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        echo 'Treinamento <b>' . $id . '</b> deletado com sucesso!';
        die;
    }   

    /**
    *
    * @Route("/create_training", name="create_training")
    */
    public function createAction(Request $request) 
    {   
        $data = $request->request->get('form');
    
        $training = new Training();
        $training->setName($data['name']);
        $training->setDescription($data['description']);
        $training->setCourse($this->get(CourseService::class)->getCourseById($data['course']));

        $response = $this->get(TrainingService::class)->addTraining($training);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        echo 'Treinamento cadastrado com sucesso. ID ' . $response;
        die;
    }   

    /**
    *
    * @Route("/add_user_training/{id}", name="add_user_training",   requirements={"id": "\d+"})
    */
    public function addUserTrainingForm($id) 
    {   

        $form = $this->createFormBuilder()
            ->setAction($this->generateUrl('insert_user_training'))
            ->add('email', TextType::class)
            ->add('training_id', HiddenType::class, array('data' => $id))
            ->add('save', SubmitType::class, array('label' => 'Inserir usuário'))
            ->getForm();

        return $this->render('training/insert_user.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    *
    * @Route("/insert_user_training", name="insert_user_training")
    */
    public function addUserTraining(Request $request) 
    { 
        $data = $request->request->get('form');

        $user = $this->get(UserService::class)->getUserByEmail($data['email']);

        if (!$user) {
            echo 'Usuário não cadastrado!';
            die;
        }

        $training = $this->get(TrainingService::class)->getTrainingById($data['training_id']);

        $training->setUsers($user);

        $response = $this->get(TrainingService::class)->addTraining($training);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        echo 'Usuário adicionado com sucesso.';
        die;
    }
}
