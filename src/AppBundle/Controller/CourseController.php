<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Course;
use AppBundle\Entity\User;
use AppBundle\Service\CourseService;
use AppBundle\Service\LocationService;
use AppBundle\Service\UserService;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CourseController extends Controller
{   

    /**
     *
     * @Route("/list_course", name="list_course")
     */
    public function listAction(Request $request) 
    {
        $courses = $this->get(CourseService::class)->getAllCourses();

        return $this->render('course/list.html.twig', array(
            'courses' => $courses,
        ));
    }

    /**
     *
     * @Route("/detail_course", name="detail_course")
     */
    public function detailAction(Request $request) 
    {    
        $id = 7;

        $response = [];

        $course = $this->get(CourseService::class)->getCourseById($id);

        $usersCoures = $course->getUsers();
        $users = [];

        foreach ($usersCoures as $user) {
            $users[] = [
                'name' => $user->getUsername(),
                'email' => $user->getEmail()
            ];
        }

        $response = [
            'id' => $id,
            'name' => $course->getName(),
            'description' => $course->getDescription(),
            'city' => $course->getCity()->getName(),
            'users' => $users
        ];

        header('Content-type: application/json');
        echo json_encode($response);
        die;
        
    }


    /**
    *
    * @Route("/create_course_form", name="create_course_form")
    */
    public function createFormAction() 
    {    
        $user = $this->get(UserService::class)->getUserById(7);
        $course = new Course();

        $form = $this->createFormBuilder($course)
            ->setAction($this->generateUrl('create_course'))
            ->add('name', TextType::class, array('label' => 'Nome do curso', 'attr' => array('class' => 'form-control')))
            ->add('description', TextType::class, array('label' => 'Descrição do curso', 'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array('attr' => array('class' => 'btn btn-primary', 'style' => 'margin-top: 20px;'),  'label' => 'Salvar curso'))
            ->getForm();

        return $this->render('course/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    *
    * @Route("/edit_course_form/{id}", name="edit_course_form",  requirements={"id": "\d+"})
    */
    public function editCourseForm($id)
    {   

        $course = $this->get(CourseService::class)->getCourseById($id);

        $form = $this->createFormBuilder($course)
            ->setAction($this->generateUrl('create_course'))
            ->add('id', HiddenType::class)
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Atualizar curso'))
            ->getForm();

        return $this->render('course/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
    *
    * @Route("/create_course", name="create_course")
    */
    public function createAction(Request $request) 
    {    
        $data = $request->request->get('form');
    
        $course = new Course();

        if (isset($data['id'])) {
            $course = $this->get(CourseService::class)->getCourseById($data['id']);
        }

        $course->setName($data['name']);
        $course->setDescription($data['description']);

        $response = $this->get(CourseService::class)->addCourse($course);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        echo 'Curso <b>' . $data['name'] . '</b> cadastrado com sucesso com o ID <b>' . $response . '</b>';
        die;
    }

    /**
    *
    * @Route("/delete_course/{id}", name="delete_course",  requirements={"id": "\d+"})
    */
    public function deleteCourse($id)
    {   

        $response = $this->get(CourseService::class)->deleteCourseById($id);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        echo 'Curso <b>' . $id . '</b> deletado com sucesso!';
        die;
    }

    /**
    *
    * @Route("/set_user_course", name="set_user_course")
    */
    public function setUserCourse()
    {   
        dump('HERE SET USER - COURSE');

        $course = new Course();

        $course = $this->get(CourseService::class)->getCourseById(7);

        $user = new User();
        $user = $this->get(UserService::class)->getUserById(4);

        $course->addUser($user);
        $user->addCourse($course);

        //Check this function - always retur true.
        $has = $course->checkHasUser($user);

        $response = $this->get(CourseService::class)->addCourse($course);

        dump($response);
        die;
    } 
}
