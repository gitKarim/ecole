<?php

namespace App\Controller;
use App\Entity\Eleve;
use App\Repository\EleveRepository;
use App\Entity\Enseignant;
use App\Repository\EnseignantRepository;
use App\Entity\Niveau;
use App\Repository\NiveauRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdministrateurController extends AbstractController
{
    /**
     * @Route("/administrateur", name="administrateur")
     */
    public function index(Request $request, ObjectManager $manager, EleveRepository $eleveRepository, EnseignantRepository $enseignantRepository, NiveauRepository $niveauRepository)
    {
    // nombe total des élèves
        $queryBuilder = $eleveRepository->createQueryBuilder('n');
        $queryBuilder->select('COUNT(eleve.nom)')
                             ->from(Eleve::class, 'eleve');
        $totalEleves = $queryBuilder->getQuery()->getSingleScalarResult();  
        
        // nombe total des enseignants
        $queryBuilder = $enseignantRepository->createQueryBuilder('n');
        $queryBuilder->select('COUNT(enseignant.nom)')
                             ->from(Enseignant::class, 'enseignant');
        $totalEnseignant = $queryBuilder->getQuery()->getSingleScalarResult();

        // nombe total des classes
        $queryBuilder = $niveauRepository->createQueryBuilder('n');
        $queryBuilder->select('COUNT(niveau.libelle)')
                             ->from(Niveau::class, 'niveau');
        $totalNiveau = $queryBuilder->getQuery()->getSingleScalarResult();
        return $this->render('administrateur/admin.html.twig', [
            'totalEleves' => $totalEleves,
            'totalEnseignant' => $totalEnseignant,
            'totalNiveau' => $totalNiveau,

        ]);
    }
}
