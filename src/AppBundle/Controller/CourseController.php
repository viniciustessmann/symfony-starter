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

class CourseController extends Controller
{
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
    * @Route("/create_course", name="create_course")
    */
    public function createAction() 
    {    

        $user = $this->get(UserService::class)->getUserById(7);
        $course = new Course();
        
        $course->setName('Name do curso');
        $course->setDescription('Descriçaõ do curso');
        $course->setCity($this->get(LocationService::class)->getCityById(1));

        $register = $this->get(CourseService::class)->addCourse($course);
        
        echo 'Course saved';
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
