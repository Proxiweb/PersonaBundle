<?php
namespace Proxiweb\Bundle\PersonaBundle\Security\Firewall;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Proxiweb\Bundle\PersonaBundle\Security\Authentication\Token\PersonaUserToken;

class PersonaListener implements ListenerInterface
{
    protected $securityContext;
    protected $authenticationManager;
    protected $checkurl;

    public function __construct(SecurityContextInterface $securityContext, AuthenticationManagerInterface $authenticationManager)
    {
        $this->securityContext = $securityContext;
        $this->authenticationManager = $authenticationManager;
        $this->checkurl = 'https://verifier.login.persona.org/verify';
    }

    public function setCheckUrl($checkurl)
    {
        $this->checkurl = $checkurl;
    }
    
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($request->get('assertion')) {
          
          $datas = http_build_query(array('assertion' => $request->get('assertion'), 'audience' => $request->getSchemeAndHttpHost()));

          $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $this->checkurl);
	  curl_setopt($ch, CURLOPT_POST, 1);
	  curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
	  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

          $res = json_decode(curl_exec($ch),true);
          curl_close($ch);
          
          if ($res['status'] == 'okay') 
          {
            $token = new PersonaUserToken();
            $token->setUser($res['email']);
            
	    try {
		$authToken = $this->authenticationManager->authenticate($token);
		$this->securityContext->setToken($authToken);
		
		return;
	    } catch (AuthenticationException $failed) {

	    }            
          }
        } 
        
        throw new AuthenticationException('Persona authentication failed');
// 	$response = new Response(json_encode(array('status' => 'Authentication failed')));
// 	$response->setStatusCode(403);
// 	$response->headers->set('Content-Type', 'application/json; charset=utf-8');
// 	$event->setResponse($response);
          
    }
}