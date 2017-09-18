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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Form\CourseType;

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
    * @Route("/create_course_form", name="create_course_form")
    */
    public function createFormAction() 
    {    
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course, ['action' => $this->generateUrl('create_course')]);

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
        $data = $request->request->get('course');
        $course = new Course();

        if (isset($data['id'])) {
            $course = $this->get(CourseService::class)->getCourseById($data['id']);
        }

        $course->setName($data['name']);
        $course->setDescription($data['description']);
        $course->setStarter($this->converterDate($data['starter']));

        $response = $this->get(CourseService::class)->addCourse($course);

        if (isset($response['error'])) {
            echo $response['message'];
            die;
        }

        $this->get('session')->getFlashBag()->add('notice', 'Curso cadastrado com sucesso!');
        return $this->redirect('list_course');
    }

    /**
     *
     * @Route("/detail_course/{id}", name="detail_course",  requirements={"id": "\d+"})
     */
    public function detailAction($id) 
    {    
        $course = $this->get(CourseService::class)->getCourseById($id);

        $info = [
            'id' => $course->getId(),
            'name' => $course->getName(),
            'description' => $course->getDescription(),
            'date' => $course->getStarter()->format('d/m/Y')
        ];

        return $this->render('course/detail.html.twig', $info);        
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

   

    private function converterDate($date)
    {
        return new \DateTime($date['year'] . '-' . $date['month'] . '-' . $date['day']);
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

        $this->get('session')->getFlashBag()->add('notice', 'Curso deletedo com sucesso!');
        return $this->redirect('/list_course');
    }

    /**
    *
    * @Route("/set_user_course/{id}", name="set_user_course",  requirements={"id": "\d+"})
    */
    public function setUserCourse($id)
    {   

        // $course = $this->get(CourseService::class)->getCourseById($id);
        // $user = $this->get(UserService::class)->getUserById(4);


        // //$course->addUser($user);
        // $user->addCourse($course);

        // //Check this function - always retur true.
        // $has = $course->checkHasUser($user);

        // $response = $this->get(CourseService::class)->addCourse($course);

        // dump($response);
        // die;
    } 
}
