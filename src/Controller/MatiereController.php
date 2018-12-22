<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Matiere ;
use App\Form\MatiereType ;
use App\Repository\MatiereRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class MatiereController extends AbstractController
{
    /**
     * @Route("/administrateur/matiere", name="page_matiere")
     */
    public function ajoutMatiere(Request $request, ObjectManager $manager, MatiereRepository $matiereRepository)
    {
        $matiere =  new Matiere();
        $form = $this->createForm(MatiereType::class, $matiere) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($matiere);
         $manager->flush();
         return $this->redirectToRoute('page_matiere');
        }
        return $this->render('security/matiere.html.twig' , [
            'formulaire'=> $form->createView(),
            'matieres' => $matiereRepository->findAll()
            
        ]) ;
}

/**
    * @Route("/administrateur/matiere/delete/{id}")
    * @Method({"DELETE"})
    */
    public function delete(Request $request, $id)
    {
        $matiere = $this->getDoctrine()->getRepository(Matiere::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($matiere);
        $entityManager->flush();

        return $this->redirectToRoute('page_matiere');
        

        }


     /**
    * @Route("administrateur/matiere/edit/{id}", name="matiereEdit_page", methods="GET|POST")
    */
    public function editMatiere(Request $request, Matiere $matiere, ObjectManager $manager, MatiereRepository $matiereRepository)
    {
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
 
            return $this->redirectToRoute('page_matiere', ['id' => $matiere->getId()]);
        }
 
        return $this->render('security/matiereEdit.html.twig' , [
            'formulaire'=> $form->createView(),
            'matieres' => $matiereRepository->findAll()
        ]);
    }



}