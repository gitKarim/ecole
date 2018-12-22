<?php

namespace App\Controller;

use App\Entity\Utilisateur ;
use App\Form\ConnexionType ;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    
   

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
  /**
	* @Route("/deconnexion", name="app_logout")
	*/
	public function logout(){}
    
    /**
     * @Route("/administrateur/user", name="ajout_user")
     */
    public function registration(Request $request, ObjectManager $manager)
    {

        $user =  new Utilisateur();
        $form = $this->createForm(ConnexionType::class, $user) ;
		
        $form->handleRequest($request);
        
        if( $form->isSubmitted() && $form->isValid() ){
			$user = $form->getData();
			$manager->persist($user);
			$manager->flush();
			return $this->redirectToRoute('ajout_user');
        }
		
        return $this->render('security/user.html.twig' , [
            'formulaire'=> $form->createView()
        ]) ;
 }

 
 }