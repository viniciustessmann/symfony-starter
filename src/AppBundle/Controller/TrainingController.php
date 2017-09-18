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
use AppBundle\Form\TrainingType;

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
     * @Route("/detail_training/{id}", name="detail_training",  requirements={"id": "\d+"})
     */
    public function detailAction($id) 
    {    
        $training = $this->get(TrainingService::class)->getTrainingById($id);
        $usersResult = $training->getUsers();

        $users = [];
        foreach ($usersResult as $user) {
            $users[] = [
                'id' => $user->getId(),
                'name' => $user->getName(),
                'email' => $user->getEmail()
            ];
        }

        $info = [
            'id' => $training->getId(),
            'name' => $training->getName(),
            'description' => $training->getDescription(),
            'date' => $training->getStarter()->format('d/m/Y'),
            'course' => $training->getCourse()->getName(),
            'courseId' => $training->getCourse()->getId(),
            'users' => $users
        ];

        return $this->render('training/detail.html.twig', $info);        
    }

    /**
    *
    * @Route("/create_training_form", name="create_training_form")
    */
    public function createFormAction(Request $request) 
    {  
        $training = new Training();

        $courses = $this->get(CourseService::class)->getAllCoursesList();

        $form = $this->createForm(TrainingType::class, $training, ['action' => $this->generateUrl('create_training'), 'courses' => $courses]);

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

        $this->get('session')->getFlashBag()->add('notice', 'Treinamento deletado com sucesso!');
        return $this->redirect('/list_training');
    }   

    /**
    *
    * @Route("/create_training", name="create_training")
    */
    public function createAction(Request $request) 
    {   
        $data = $request->request->get('training');

        $training = new Training();
        $training->setName($data['name']);
        $training->setDescription($data['description']);
        $training->setStarter($this->converterDate($data['starter']));
        $training->setCourse($this->get(CourseService::class)->getCourseById($data['course']));

        $response = $this->get(TrainingService::class)->addTraining($training);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        $this->get('session')->getFlashBag()->add('notice', 'Treinamento cadastrado com sucesso!');
        return $this->redirect('list_training');
    }   

    private function converterDate($date)
    {
        return new \DateTime($date['year'] . '-' . $date['month'] . '-' . $date['day']);
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
    * @Route("/insert_user_training/{id}", name="insert_user_training", requirements={"id": "\d+"})
    */
    public function addUserTraining($id) 
    {    
        $user = $this->get('security.token_storage')->getToken()->getUser();
    
        if (!$user) {
            $this->get('session')->getFlashBag()->add('notice', 'Usuário não encontrado');
            return $this->redirect('/list_training');
        }


        $training = $this->get(TrainingService::class)->getTrainingById($id);

        $training->setUsers($user);

        $response = $this->get(TrainingService::class)->addTraining($training);

        if (isset($response['error'])) {
            $this->get('session')->getFlashBag()->add('notice', 'Error ao adicionar usuário ao treinamento - ' . $response['message']);
            return $this->redirect('/list_training');
        }

        $this->get('session')->getFlashBag()->add('notice', 'Usuário adicionado com sucesso ao treinamento.');
        return $this->redirect('/list_training');
    }
}
