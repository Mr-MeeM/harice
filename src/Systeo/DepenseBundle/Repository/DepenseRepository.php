<?php

namespace Systeo\DepenseBundle\Repository;
use Doctrine\ORM\QueryBuilder;

/**
 * DepenseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DepenseRepository extends \Doctrine\ORM\EntityRepository
{
    public function MyFindAll($data)
   {
       $queryBuilder = $this->createQueryBuilder('a');
       
       $queryBuilder->leftJoin('a.tier', 'tier')
           ->addSelect('tier');
       
       $this->searchName($queryBuilder,$data);
       $this->searchTier($queryBuilder,$data);
       $this->searchCategory($queryBuilder,$data);
       $this->searchMontantHt($queryBuilder, $data);
       $this->searchMontantTva($queryBuilder, $data);
       $this->searchMontantTtc($queryBuilder, $data);
       $this->searchSolde($queryBuilder, $data);
       $this->searchDate($queryBuilder, $data);
      
       $queryBuilder->orderBy('a.date','DESC');
       
       return $queryBuilder;
   }
   
   public function getSumOperations($data){
       $querySum = $this->createQueryBuilder('a');
       
        $querySum->leftJoin('a.tier', 'tier')
           ->addSelect('tier');
       
       $querySum->select('SUM(a.solde) as SOLDE,  SUM(a.montantHt) as HT,  SUM(a.montantTva) as TVA, SUM(a.montantTtc) as TTC');
       
       $this->searchName($querySum,$data);
       $this->searchTier($querySum,$data);
       $this->searchCategory($querySum,$data);
       $this->searchMontantHt($querySum, $data);
       $this->searchMontantTva($querySum, $data);
       $this->searchMontantTtc($querySum, $data);
       $this->searchSolde($querySum, $data);
       $this->searchDate($querySum, $data);
       
       $result = $querySum->getQuery()->execute();
       
       return $result[0];
   }
   
   /**
    * 
    * @param type $name
    */
   private function searchName(QueryBuilder $qb, $data)
   {
       if(isset($data['name']) && !empty($data['name'])){
           $qb->andWhere('a.name like :name')
              ->setParameter('name', '%'.$data['name'].'%');
       }
   }
   
   /**
    * 
    * @param type $name
    */
   private function searchTier(QueryBuilder $qb, $data)
   {
       if(isset($data['tier']) && !empty($data['tier'])){
           $qb->andWhere('tier.rs like :tier OR tier.firstName like :tier OR tier.lastName like :tier')
           
              ->setParameter('tier', '%'.$data['tier'].'%');
       }
   }
   
   
   
   /**
    * 
    * @param type $name
    */
   private function searchMontantHt(QueryBuilder $qb, $data)
   {
       if(isset($data['montantHt']) && !empty($data['montantHt']) && isset($data['montantHt_comparateur']) && !empty($data['montantHt_comparateur'])){
           $qb->andWhere('a.montantHt '.$data['montantHt_comparateur'].' :montantHt')
              ->setParameter('montantHt', $data['montantHt']);
       }
   }
   
   /**
    * 
    * @param type $name
    */
   private function searchMontantTva(QueryBuilder $qb, $data)
   {
       if(isset($data['montantTva']) && !empty($data['montantTva']) && isset($data['montantTva_comparateur']) && !empty($data['montantTva_comparateur'])){
           $qb->andWhere('a.montantTva '.$data['montantTva_comparateur'].' :montantTva')
              ->setParameter('montantTva', $data['montantTva']);
       }
   }
   
   /**
    * 
    * @param type $name
    */
   private function searchMontantTtc(QueryBuilder $qb, $data)
   {
       if(isset($data['montantTtc']) && !empty($data['montantTtc']) && isset($data['montantTtc_comparateur']) && !empty($data['montantTtc_comparateur'])){
           $qb->andWhere('a.montantTtc '.$data['montantTtc_comparateur'].' :montantTtc')
              ->setParameter('montantTtc', $data['montantTtc']);
       }
   }
   
   /**
    * 
    * @param type $name
    */
   private function searchSolde(QueryBuilder $qb, $data)
   {
       if(isset($data['solde'])  && isset($data['solde_comparateur']) && !empty($data['solde_comparateur'])){
           $qb->andWhere('a.solde '.$data['solde_comparateur'].' :solde')
              ->setParameter('solde', $data['solde']);
       }
   }
   
   private function searchDate(QueryBuilder $qb, $data) {

        $date_1 = false;
        $date_2 = false;

        if (isset($data['date_debut']) && !empty($data['date_debut'])) {
            $date_1 = true;
        }

        if (isset($data['date_fin']) && !empty($data['date_fin'])) {
            $date_2 = true;
        }

        if ($date_1 && !$date_2) {
            $qb->andWhere('a.date >= :date')
                    ->setParameter('date', $data['date_debut']);
        } elseif (!$date_1 && $date_2) {
            $qb->andWhere('a.date <= :date')
                    ->setParameter('date', $data['date_fin']);
        } elseif ($date_1 && $date_2) {
            $qb->andWhere('a.date BETWEEN :start AND :end')
                    ->setParameter('start', $data['date_debut'])
                    ->setParameter('end', $data['date_fin'])
            ;
        }
    }
    
   private function searchCategory(QueryBuilder $qb, $data) {
       
       $rep = $this->getEntityManager()->getRepository("SysteoDepenseBundle:DepenseCategory");
       
       
       if(isset($data['depenseCategory']) && !empty($data['depenseCategory'])){
           $categ = $rep->findOneById($data['depenseCategory']);
          
           if(!is_null($categ)){
              
               $query = $rep->createQueryBuilder('dc');
               
               $query->where('dc.root = :root AND dc.lft >= :lft AND dc.rgt <= :rgt')
                      ->setParameter('root', $categ->getRoot())
                      ->setParameter('lft', $categ->getLft())
                      ->setParameter('rgt', $categ->getRgt());
         
                $results = $query->getQuery()->execute();

                $tab = [];

                foreach($results as $rt):
                    $tab[] = $rt->getId();
                endforeach;

              
               $qb->andWhere('a.depenseCategory IN (:categs)')
              ->setParameter('categs', $tab);
              
           }
       }
       
   }
    
}
