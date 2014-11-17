<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * TransactionsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TransactionsRepository extends EntityRepository
{
	public function filterProspects($postdata, $loaded_properties)
	{
		$offset = rand(0,99000);
		$loaded_properties = implode(',', $loaded_properties);
		
		return $this->getEntityManager()
			->createQuery(
				'SELECT t.id, 
						t.urakey, 
						t.price,
						t.sector,
						t.newprice,						
						t.districtcode,
						pr.longitude,
						pr.latitude
					FROM RefiBundle:Prospectloan pl
					JOIN RefiBundle:Transactions t
						WITH t.id = pl.transactionId
					JOIN RefiBundle:Postalregion pr
						WITH t.districtcode = pr.regionCode
					WHERE t.id NOT IN (:loaded_properties)
				'
			)
			->setParameter('loaded_properties', $loaded_properties)
			->setMaxResults($postdata['limit'])
        	->setFirstResult($offset)
			->getArrayResult();
	}

	public function fetchProspectByTransactionsId($id)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT p.id,
						p.age,
						p.derivedIncome
					FROM RefiBundle:Prospect p
					LEFT JOIN RefiBundle:Prospectproperty pp
					WITH p.id = pp.prospectId  
					WHERE pp.salesId = :ppSalesId
				'
			)
			->setParameter('ppSalesId', $id)
			->getArrayResult();
	}
	
	public function fetchLoanByTransactionsAndProspectId($tid, $pid)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT pl.loanAmount,
						pl.ltv,
						pl.loanDate
					FROM RefiBundle:Prospectloan pl
					WHERE pl.transactionId = :tId
					AND pl.prospectId = :pId
				'
			)
			->setParameter('tId', $tid)
			->setParameter('pId', $pid)
			->getArrayResult();
	}
	
	public function fetchAssetsByProspectId($pid)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT 
					  COUNT(pl.transactionId) as count_tid,
					  SUM(t.newprice) as sum_nprice
					FROM
					  RefiBundle:Prospectloan pl 
					JOIN RefiBundle:Transactions t 
					WITH pl.transactionId = t.id
					WHERE pl.prospectId = :pId
				'
			)
			->setParameter('pId', $pid)
			->getArrayResult();
	}
}