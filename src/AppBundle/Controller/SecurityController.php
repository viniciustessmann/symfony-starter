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
use AppBundle\Form\UserType;

class SecurityController extends Controller
{   

    /**
     *
     * @Route("/list_user/{type}", name="list_user", requirements={"id": "\d+"})
     */
    public function listAction($type) 
    {    
        $filter = 'learner';

        if (strpos($type, 'tutor') !== false) {
            $filter = 'tutor';
        }

        $users = $this->get(UserService::class)->getAllUsers($filter);

        return $this->render('user/list.html.twig', array(
            'users' => $users,
        ));
    }

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
     * @Route("/create_user_form_learner", name="create_user_form_learner")
     */
    public function createFormAction(Request $request) 
    {    
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user, ['action' => $this->generateUrl('create_user')]);

        return $this->render('user/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/create_user_form_tutor", name="create_user_form_tutor")
     */
    public function createFormTutorAction(Request $request) 
    {    
        $user = new User();
        
        $form = $this->createForm(UserType::class, $user, ['action' => $this->generateUrl('create_user')]);

        return $this->render('user/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     *
     * @Route("/create_user", name="create_user")
     */
    public function createAction(Request $request) 
    {       
        $data = $request->request->get('user');

        $role = 'ROLE_LEARNER';
        if (strpos($request->headers->get('referer'), 'create_user_form_tutor') !== false) {
            $role = 'ROLE_TUTOR';
        }

        $user = new User();

        $plainPassword = '123456';
        $encoder_service = $this->get('security.encoder_factory');
        $encoder = $encoder_service->getEncoder($user);

        $user->setUsername($data['name'] . date('Y-m-d h:i:s'));
        $user->setName($data['name'] . date('Y-m-d h:i:s'));
        $user->setEmail($data['email'] . date('Y-m-d h:i:s'));
        $user->setSalt(md5(uniqid(null, true)));
        $password = $encoder->encodePassword('123456', $user->getSalt());

        $user->setConfirmationToken(md5(date('Y-m-d h:i:s')));
        $user->setPassword($password);
        $user->addRole($role);

        $userRegister = $this->get(UserService::class);
        $userId = $userRegister->registerUser($user);


        if (isset($userId['error'])) {
            echo json_encode($userId);
            die;
        }

        $mailerService = $this->get(MailerService::class);
        
        $content = self::getContent($userId);
        $responseMailer = $mailerService->sendEmail('viniciusschleetessmann@gmail.com', 'Ative sua conta', $content);

        $this->get('session')->getFlashBag()->add('notice', 'Cadastro realizado com sucesso! acesse seu e-mail para ativar a conta.');
        return $this->redirect('/list_user/tutor');
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
     * @Route("/delete_user/{id}", name="delete_user", requirements={"id": "\d+"})
     */
    public function deleteAction($id) 
    {    
        $user = $this->get(UserService::class)->getUserById($id);
        $user = $this->get(UserService::class)->deleteUser($user);

        header('Content-type: application/json');

        if ($user['error']) {
            $this->get('session')->getFlashBag()->add('notice', 'Erro ao apagar usuário. ' . $user['message']);
            return $this->redirect('/list_user/learner');
        }

        $this->get('session')->getFlashBag()->add('notice', 'Usuário apagado com sucesso');
        return $this->redirect('/list_user/learner');
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

    /**
     *
     * @Route("/logout", name="logout")
     */
    public function logoutUser() 
    { 
        
    }
}
