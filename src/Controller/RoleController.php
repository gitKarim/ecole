<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Role ;
use App\Form\RoleType ;
use App\Repository\RoleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RoleController extends AbstractController
{
    /**
     * @Route("/administrateur/role", name="page_role")
     */
    public function ajoutRole(Request $request, ObjectManager $manager, RoleRepository $roleRepository)
    {
        $role =  new Role();
        $form = $this->createForm(RoleType::class, $role) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($role);
         $manager->flush();
         return $this->redirectToRoute('page_role');
        }
        return $this->render('security/role.html.twig' , [
            'formulaire'=> $form->createView(),
            'roles' => $roleRepository->findAll()
            
        ]) ;
        
        
 }
   /**
    * @Route("administrateur/role/edit/{id}", name="roleEdit_page", methods="GET|POST")
    */
    public function editRole(Request $request, Role $role, ObjectManager $manager, RoleRepository $roleRepository)
    {
        $form = $this->createForm(RoleType::class, $role);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
 
            return $this->redirectToRoute('page_role', ['id' => $role->getId()]);
        }
 
        return $this->render('security/roleEdit.html.twig' , [
            'formulaire'=> $form->createView(),
            'roles' => $roleRepository->findAll()
        ]);
    }  

    /**
    * @Route("/administrateur/role/delete/{id}")
    * @Method({"DELETE"})
    */
    public function delete(Request $request, $id)
    {
        $role = $this->getDoctrine()->getRepository(Role::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($role);
        $entityManager->flush();

        return $this->redirectToRoute('page_role');
        

        }

}
