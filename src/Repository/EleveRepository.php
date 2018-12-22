<?php

namespace App\Repository;

use App\Entity\Eleve;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Eleve|null find($id, $lockMode = null, $lockVersion = null)
 * @method Eleve|null findOneBy(array $criteria, array $orderBy = null)
 * @method Eleve[]    findAll()
 * @method Eleve[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EleveRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Eleve::class);
    }
  
    // fonction afficher uniquement les filles de l'etablissemnt
    public function findOnlyFemale()
    {
        return $this->findBy(array('genre' => 'F'));
    }
   
    // fonction afficher uniquement les garçons de l'etablissemnt   
     public function findOnlyMale()
    {
        return $this->findBy(array('genre' => 'F'));
    }
    // fonction afficher les élèves par classe dans la fiche élève
    
    public function findByClasse($classe)
    {
        return $this->findBy(array('niveau' => $classe));
    }

    public function findByEleve($classe){
        return $this->findBy(array('libelle' => $classe));
    }
    // /**
    //  * @return Eleve[] Returns an array of Eleve objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Eleve
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
