<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;
use AppBundle\Service\UserService;

class SecurityController extends Controller
{   
    /**
     *
     * @Route("/create_user", name="create_user")
     */
    public function createAction(Request $request) 
    {    
        $userRegister = $this->get(UserService::class);
        $userId = $userRegister->registerUser($request);
        
        header('Content-type: application/json');

        $response = [
            'success' => true,
            'userId' => $userId
        ];

        echo json_encode($response);
        die;

    }
}
