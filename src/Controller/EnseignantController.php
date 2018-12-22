<?php

namespace App\Controller;

use App\Entity\Enseignant ;
use App\Form\EnseignantType ;
use App\Repository\EnseignantRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EnseignantController extends AbstractController
{
    
       /**
     * @Route("/administrateur/enseignant", name="page_enseignant")
     */
    public function ajoutEnseignant(Request $request, ObjectManager $manager,  EnseignantRepository $enseignantRepository)
    {
        $enseignant =  new Enseignant();
        $form = $this->createForm(EnseignantType::class, $enseignant) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($enseignant);
         $manager->flush();
         return $this->redirectToRoute('page_enseignant');
        }

        // nombe total des enseignants
        $queryBuilder = $enseignantRepository->createQueryBuilder('n');
        $queryBuilder->select('COUNT(enseignant.nom)')
                             ->from(Enseignant::class, 'enseignant');
        $totalenseignant = $queryBuilder->getQuery()->getSingleScalarResult(); 
        return $this->render('security/enseignant.html.twig' , [
            'formulaire'=> $form->createView(),
            'enseignants' => $enseignantRepository->findAll(), 
            'totalEnseignant' => $totalenseignant, 
            
        ]);
 }
}
