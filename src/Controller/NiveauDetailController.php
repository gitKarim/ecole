<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Eleve;
use App\Entity\Enseignant;
use App\Entity\Niveau;
use App\Entity\Matiere;
use App\Repository\MatiereRepository; 

use App\Repository\NiveauRepository;
use App\Repository\EleveRepository;
use App\Repository\EnseignantRepository;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;



class NiveauDetailController extends AbstractController
{
    /**
     * @Route("administrateur/niveau/detail", name="niveau_detail")
     */
    public function index(Request $request, ObjectManager $manager,MatiereRepository $matiereRepository, NiveauRepository $niveauRepository, EleveRepository $eleveRepository, EnseignantRepository $enseignantRepository)
    {
        
        // https://stackoverflow.com/questions/44463838/symfony-doctrine-createquerybuilder-from-alias-not-working
       
        $queryBuilder = $niveauRepository->createQueryBuilder('libelle');
        
        $queryBuilder->select('COUNT(n.libelle)')
                     ->from(Niveau::class, 'n')
                     ->where('n.id = 1 ');
        $query = $queryBuilder->getQuery()->getSingleScalarResult() ;
        

        
        
        return $this->render('security/niveauDetail.html.twig', [
            'niveaux' => $eleveRepository->findAll(),
            'total' => $query,
        ]);
    }
}
