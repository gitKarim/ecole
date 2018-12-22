<?php

namespace App\Controller;

use App\Entity\Eleve ;
use App\Form\EleveType ;
use App\Repository\EleveRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class EleveController extends Controller
{
    
     /**
     * @Route("/administrateur/eleve", name="page_eleve")
     */
    public function ajoutEleve(Request $request, ObjectManager $manager, EleveRepository $eleveRepository )
    {
        $eleve =  new Eleve();
        $form = $this->createForm(EleveType::class, $eleve) ;
        $form->handleRequest($request);
        
        if( $form->IsSubmitted() && $form->Isvalid() ){
         $manager->persist($eleve);
         $manager->flush();
         return $this->redirectToRoute('page_eleve');
        }

/*
 * @route("/administrateur/eleve" , "name=page_eleve")
 */



        /**
         * @var $paginator \Knp\Component\Pager\PaginatorInterface
         */
      
         $queryBuilder = $eleveRepository->findAll()  ;
        $paginator  = $this->get('knp_paginator');

        $result = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 5)
        );
        # fonction de filtre

        $queryBuilderSearch = $eleveRepository->createQueryBuilder('e');
        if($request->query->getAlnum('filter')){
            $queryBuilderSearch->where('e.nom Like :nom')
                ->setParameter('nom', '%' . $request->query->getAlnum('filter'). '%');
        }
        
    





         #### les statistiques #########

        // total des garçons
        $queryBuilderMale = $eleveRepository->createQueryBuilder('n');
        $queryBuilderMale->select('COUNT(n.nom)')
//                         ->from(Eleve::class, 'eleve')
                         ->where('n.genre = :M')
                         ->setParameter('M','M') ;

        // total des filles
        $queryBuilderFemale = $eleveRepository->createQueryBuilder('n');
        $queryBuilderFemale->select('COUNT(n.nom)')
//                           ->from(Eleve::class, 'eleve')
                           ->where('n.genre = :F')
                           ->setParameter('F','F') ;

        // nombe total des élèves
        $queryBuilder = $eleveRepository->createQueryBuilder('n');
// suite à un resultat erroné (resultat * resultat)
//        $queryBuilder->select('COUNT(eleve.nom)')
        $queryBuilder->select('COUNT(n.nom)');
//                     ->from(Eleve::class, 'eleve');

        $totalElevesMale = $queryBuilderMale->getQuery()->getSingleScalarResult();
        $totalElevesFemale = $queryBuilderFemale->getQuery()->getSingleScalarResult();
        $totalEleves = $queryBuilder->getQuery()->getSingleScalarResult();

        return $this->render('security/eleve.html.twig' , [
            'formulaire'=> $form->createView(),
            'totalEleves' => $totalEleves,
            'totalElevesMale' => $totalElevesMale ,
            'totalElevesFemale' => $totalElevesFemale ,
            'eleves'=> $result,
            // a trouver l'id du niveau pour le passer dans $variable
            'elevesParClasse' => $eleveRepository->findByClasse(1) ,
            'filles' => $eleveRepository->findOnlyFemale(),
            'garçons' => $eleveRepository->findOnlyMale()

        ]) ;


}

    /**
    * @Route("/administrateur/eleve/delete/{id}")
    * @Method({"DELETE"})
    */
    public function delete(Request $request, $id)
    {
        $eleve = $this->getDoctrine()->getRepository(Eleve::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($eleve);
        $entityManager->flush();

        return $this->redirectToRoute('page_eleve');

    }

       /**
    * @Route("administrateur/eleve/edit/{id}", name="eleveEdit_page", methods="GET|POST")
    */
    public function editeleve(Request $request, Eleve $eleve, ObjectManager $manager, EleveRepository $eleveRepository)
    {
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
 
            return $this->redirectToRoute('page_eleve', ['id' => $eleve->getId()]);
        }
 
        return $this->render('security/eleveEdit.html.twig' , [
            'formulaire'=> $form->createView(),
            'eleves' => $eleveRepository->findAll()
        ]);
    }

    /**
     * @Route("administrateur/eleve/read/{id}", name="eleveRead_page", methods="GET|POST")
     */
    public function readEleve($id, Request $request,  Eleve $eleve, ObjectManager $manager, eleveRepository $eleveRepository)
    {
        $form = $this->createForm(EleveType::class, $eleve);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('page_eleve', ['id' => $eleve->getId()]);
        }
        
        

        $classe = $eleve->getNiveau();

        return $this->render('security/eleveRead.html.twig' , [
            'formulaire'=> $form->createView(),
            'eleves' => $eleveRepository->find($id),
            'elevesParClasse' => $eleveRepository->findByClasse($classe)



        ]);
        
    }

        
}
