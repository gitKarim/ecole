<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Evenement ;
use App\Form\EventType ;
use App\Repository\EvenementRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class EventController extends AbstractController
{
    /**
     * @Route("/administrateur/evenement", name="page_evenement")
     */
    public function ajoutEvenement(Request $request, ObjectManager $manager, EvenementRepository $evenementRepository)
    {
        $evenement =  new Evenement();
        $form = $this->createForm(EventType::class, $evenement) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($evenement);
         $manager->flush();
         return $this->redirectToRoute('page_evenement');
        }
        return $this->render('security/evenement.html.twig' , [
            'formulaire'=> $form->createView(),
            'evenements' => $evenementRepository->findAll()  ]) ;

            
            
       
}

 /**
     * @Route("/", name="page_evenement_acceuil")
     */
    public function afficheEvenementAcceuil(Request $request, ObjectManager $manager, EvenementRepository $evenementRepository)
    {

        return $this->render('home/acceuil.html.twig' , ['evenements' => $evenementRepository->findAll()  ]) ;
           
      
}

     /**
     * @Route("/events", name="page_evenements")
     */
    public function afficheEvenements(Request $request, ObjectManager $manager, EvenementRepository $evenementRepository)
    {
      $dateEvenement = $evenementRepository->findAll() ; 

        return $this->render('home/events.html.twig' , ['evenements' => $evenementRepository->findAll()  ]) ;
           
      
}

    /**
     * @Route("/administrateur/evenement/delete/{id}")
     * @Method({"DELETE"})
     */
    public function delete(Request $request, $id)
    {
        $evenement = $this->getDoctrine()->getRepository(Evenement::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($evenement);
        $entityManager->flush();

        return $this->redirectToRoute('page_evenement');


    }


    /**
     * @Route("administrateur/evenement/edit/{id}", name="evenementEdit_page", methods="GET|POST")
     */
    public function editEvenement(Request $request, Evenement $evenement, ObjectManager $manager, EvenementRepository $evenementRepository)
    {
        $form = $this->createForm(EventType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('page_evenement', ['id' => $evenement->getId()]);
        }

        return $this->render('security/evenementEdit.html.twig' , [
            'formulaire'=> $form->createView(),
            'evenements' => $evenementRepository->findAll()
        ]);
    }
}
