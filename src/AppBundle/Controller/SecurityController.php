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
     * @Route("/detail_user", name="detail_user")
     */
    public function detailAction(Request $request) 
    {    
        $userRegister = $this->get(UserService::class);
        $user = $userRegister->infoUser($request);

        header('Content-type: application/json');

        if (isset($userId['error'])) {
            echo json_encode($userId);
            die;
        }
        
        echo json_encode($user);
        die;
    }

    /**
     *
     * @Route("/create_user", name="create_user")
     */
    public function createAction(Request $request) 
    {    
        $userRegister = $this->get(UserService::class);
        $userId = $userRegister->registerUser($request);

        header('Content-type: application/json');

        if (isset($userId['error'])) {
            echo json_encode($userId);
            die;
        }

        $response = [
            'success' => true,
            'message' => 'Success add user',
            'userId' => $userId
        ];
        
        echo json_encode($response);
        die;
    }

    /**
     *
     * @Route("/edit_user", name="edit_user")
     */
    public function editAction(Request $request) 
    {    
        $userRegister = $this->get(UserService::class);
        $user = $userRegister->editUser($request);

        header('Content-type: application/json');

        if ($user['error']) {
            echo json_encode($user);
            die;
        }

        $response = [
            'success' => true,
            'message' => 'Success edit user',
            'userId' => $user
        ];

        echo json_encode($response);
        die;
    }

    /**
     *
     * @Route("/delete_user", name="delete_user")
     */
    public function deleteAction(Request $request) 
    {    
        $userRegister = $this->get(UserService::class);
        $user = $userRegister->deleteUser($request);

        header('Content-type: application/json');

        if ($user['error']) {
            echo json_encode($user);
            die;
        }

        $response = [
            'success' => true,
            'message' => 'Success delete user',
            'userId' => $user
        ];

        echo json_encode($response);
        die;
    }

    /**
     *
     * @Route("/enable_user", name="enable_user")
     */
    public function enableAction(Request $request) 
    {    
        $userRegister = $this->get(UserService::class);
        $user = $userRegister->enableUser($request);

        header('Content-type: application/json');

        if (isset($user['error'])) {
            echo json_encode($user);
            die;
        }

        $response = [
            'success' => true,
            'message' => 'Success actived user',
            'userId' => $user
        ];

        echo json_encode($response);
        die;
    }

}
