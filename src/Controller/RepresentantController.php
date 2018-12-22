<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Representant ;
use App\Form\RepresentantType ;
use App\Repository\RepresentantRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class RepresentantController extends AbstractController
{
    /**
     * @Route("/administrateur/representant", name="page_representant")
     */
    public function ajoutRepresentant(Request $request, ObjectManager $manager, RepresentantRepository $representantRepository)
    {
        $representant =  new Representant();
        $form = $this->createForm(RepresentantType::class, $representant) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($representant);
         $manager->flush();
         return $this->redirectToRoute('page_representant');
        }
        return $this->render('security/representant.html.twig' , [
            'formulaire'=> $form->createView(),
            'representants' => $representantRepository->findAll()
            
        ]) ;
}

/**
    * @Route("/administrateur/representant/delete/{id}")
    * @Method({"DELETE"})
    */
    public function delete(Request $request, $id)
    {
        $representant = $this->getDoctrine()->getRepository(Representant::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($representant);
        $entityManager->flush();

        return $this->redirectToRoute('page_representant');
        

        }


     /**
    * @Route("administrateur/representant/edit/{id}", name="representantEdit_page", methods="GET|POST")
    */
    public function editRepresentant(Request $request, representant $representant, ObjectManager $manager, RepresentantRepository $representantRepository)
    {
        $form = $this->createForm(RepresentantType::class, $representant);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
 
            return $this->redirectToRoute('page_representant', ['id' => $representant->getId()]);
        }
 
        return $this->render('security/representantEdit.html.twig' , [
            'formulaire'=> $form->createView(),
            'representants' => $representantRepository->findAll()
        ]);
    }

 /**
     * @Route("administrateur/representant/read/{id}", name="representantRead_page", methods="GET|POST")
     */
    public function readRepresentant($id, Request $request, Representant $representant, ObjectManager $manager, representantRepository $representantRepository)
    {
        $form = $this->createForm(RepresentantType::class, $representant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('representant', ['id' => $representant->getId()]);
        }
        
        return $this->render('security/representantRead.html.twig' , [
            'formulaire'=> $form->createView(),
            'representants' => $representantRepository->find($id)
        ]);
        
    }

}