<?php
namespace JoranBeaufort\Neo4jUserBundle\Security\Authentication\Handler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
 
class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    protected 
        $router,
        $security;
    
    public function __construct(Router $router, AuthorizationChecker $security)
    {
        $this->router = $router;
        $this->security = $security;
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // URL for redirect the user to where they were before the login process begun if you want.
        // $referer_url = $request->headers->get('referer');
        
        // Default target for unknown roles. Everyone else go there.
        $url = 'dashboard';
        $user = $token->getUser();
        
        if($user->getUserTeam() == null || $user->getUserTeam() == false) {
            $url = 'team_apply';
        }
        elseif($user->getUsername() != null && $user->getUserTeam() != false) {
            $url = 'dashboard';
        }
        $response = new RedirectResponse($this->router->generate($url));
            
        return $response;
    }
}