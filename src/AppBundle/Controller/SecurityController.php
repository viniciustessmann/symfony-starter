<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Doctrine\UserManager;
use AppBundle\Entity\User;

class SecurityController extends Controller
{   
    /**
     *
     * @Route("/create_user", name="create_user")
     */
    public function createAction(Request $request) 
    {    
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setUsername($request->request->get('name'));
        $user->setEmail($request->request->get('email'));
        $user->setConfirmationToken(md5(date('Y-m-d h:i:s')));

        $em->persist($user);
        $em->flush();

        die;    
    }
}
