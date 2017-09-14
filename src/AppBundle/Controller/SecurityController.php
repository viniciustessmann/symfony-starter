<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;
use AppBundle\Service\UserService;
use AppBundle\Service\MailerService;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Form\FormBuilder;

class SecurityController extends Controller
{   
    /**
     *
     * @Route("/detail_user", name="detail_user")
     */
    public function detailAction(Request $request) 
    {    
        $userRegister = $this->get(UserService::class);
        $user = $userRegister->infoUser($this->getUser()->getId());

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

        $mailerService = $this->get(MailerService::class);
        
        $content = self::getContent($userId);
        //$responseMailer = $mailerService->sendEmail('viniciusschleetessmann@gmail.com', 'Ative sua conta', $content);

        $response = [
            'success' => true,
            'message' => 'Success add user',
            'userId' => $userId,
            'link' => $content
        ];
        
        echo json_encode($response);
        die;
    }

    public function getContent($userId)
    {   
        $user = $this->get(UserService::class)->getUserById($userId);
        $link = 'http://127.0.0.1:8000/app_dev.php/enable_user?user=' . $userId . '&token=' . $user->getConfirmationToken();
        //return '<html><body>Acesse o <a href="' . $link . '">link</a> para ativar sua conta. </body></html>';
        return $link;
    }

    /**
     *
     * @Route("/edit_user", name="edit_user")
     */
    public function editAction(Request $request) 
    {    
        $userRegister = $this->get(UserService::class);
        $user = $userRegister->editUser($this->getUser()->getId());

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

    /**
     *
     * @Route("/switch_user_after_login", name="switch_user_after_login")
     */
    public function swichUserAfterLogin() 
    { 
        $roles = $this->getUser()->getRoles();
        
        if (in_array('ROLE_TUTOR', $roles)) {
            echo 'Redirect to tutor area';
            die;
        }

        echo 'Redirect to learner area';
        die;
    }
}
