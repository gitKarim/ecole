<?php

namespace App\Controller;

use App\Entity\Eleve;
use App\Entity\Niveau ;
use App\Form\NiveauType ;
use App\Repository\NiveauRepository;
use App\Repository\EleveRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NiveauController extends AbstractController
{
    /**
     * @Route("/administrateur/niveau", name="page_niveau")
     */
    public function ajoutNiveau(Request $request, ObjectManager $manager, NiveauRepository $niveauRepository, EleveRepository $eleveRepository)
    {
        $niveau =  new Niveau();
        $form = $this->createForm(NiveauType::class, $niveau) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($niveau);
         $manager->flush();
         return $this->redirectToRoute('page_niveau');
        }


        return $this->render('security/niveau.html.twig' , [
            'formulaire'=> $form->createView(),
             'niveaux' => $niveauRepository->findAll()

            
        ]) ;

}



    /**
    * @Route("administrateur/niveau/edit/{id}", name="niveauEdit_page", methods="GET|POST")
    */
    public function editNiveau(Request $request, Niveau $niveau, ObjectManager $manager, NiveauRepository $niveauRepository)
    {
        $form = $this->createForm(NiveauType::class, $niveau);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
 
            return $this->redirectToRoute('page_niveau', ['id' => $niveau->getId()]);
        }
 
        return $this->render('security/niveauEdit.html.twig' , [
            'formulaire'=> $form->createView(),
            'niveaux' => $niveauRepository->findAll()
        ]);
    }  

    /**
    * @Route("/administrateur/niveau/delete/{id}")
    * @Method({"DELETE"})
    */
    public function delete(Request $request, Niveau $niveau, ObjectManager $manager, NiveauRepository $niveauRepository, $id)
    {
        $niveau = $this->getDoctrine()->getRepository(Niveau::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($niveau);
        $entityManager->flush();

        return $this->redirectToRoute('page_niveau');
        

        }
    /**
     * @Route("/administrateur/niveau/detail", name="page_niveau_detail")
     */
    public function detailNiveau(Request $request, ObjectManager $manager,EleveRepository $eleveRepository,  NiveauRepository $niveauRepository)
    {
        $classe = '6A' ;
        return $this->render('security/niveauDetail.html.twig' , [

            'niveaux' => $niveauRepository->findAll(),
            'elevesParClasse' => $eleveRepository->findByEleve($classe)


        ]) ;



    }
}