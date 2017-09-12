<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;
use AppBundle\Service\UserService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;

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

        $user = new User();

        $plainPassword = 'ryanpass';
        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($user);

        $user->setSalt(md5(uniqid(null, true)));
        $password = $encoder->encodePassword('123456', $user->getSalt());

        $user->setUsername($request->request->get('name') . date('h:i:s'));
        $user->setEmail($request->request->get('email')  . date('h:i:s'));
        $user->setConfirmationToken(md5(date('Y-m-d h:i:s')));
        $user->setPassword($password);
        $user->addRole($request->request->get('role'));


        $userRegister = $this->get(UserService::class);
        $userId = $userRegister->registerUser($user);

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
