<?php

namespace Proxiweb\Bundle\PersonaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class PersonaController extends Controller
{
    /**
     * @Route("/persona/login", name="persona_login")
     */
    public function loginAction()
    {
        $response = new Response(json_encode(array('email' =>$this->get('security.context')->getToken()->getUser()->getEmail())));
	$response->setStatusCode(200);
	$response->headers->set('Content-Type', 'application/json; charset=utf-8');
	return $response;      
    }
    
    /**
     * @Route("/persona/logout", name="persona_logout")
     */
    public function logoutAction()
    {
	$session = $this->getRequest()->getSession();
	$token = $session->get('token');
	$this->get("request")->getSession()->invalidate();
	$this->get("security.context")->setToken(null);
	$this->getRequest()->getSession()->remove('token'); 
	
	$response = new Response('logged out');
	return $response;
    }
    
    /**
     * @Route("/demo/persona")
     * @Template()
     */
    public function demoAction()
    {
         return array();
    }    
       
}
