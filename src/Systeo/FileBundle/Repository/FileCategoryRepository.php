<?php

namespace Systeo\FileBundle\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * FileCategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FileCategoryRepository extends \Doctrine\ORM\EntityRepository
{
     public function MyFindAll($data)
   {
       $queryBuilder = $this->createQueryBuilder('a');
       
       $queryBuilder->orderBy('a.name');
       
       $this->searchName($queryBuilder,$data);
       $this->searchActive($queryBuilder,$data);
       
       $query = $queryBuilder->getQuery();
       $results = $query->getResult();
       
       return $results;
   }
   
   /**
    * 
    * @param type $name
    */
   private function searchName(QueryBuilder $qb, $data)
   {
       if(isset($data['search']) && !empty($data['search'])){
           $qb->andWhere('a.name like :search ')
              ->setParameter('search', '%'.$data['search'].'%');
       }
   }
   
   private function searchActive(QueryBuilder $qb, $data)
   {
       if(isset($data['active']) && $data['active']!=''){
           $qb->andWhere('a.active = :active')
              ->setParameter('active', $data['active']);
       }
   }
}