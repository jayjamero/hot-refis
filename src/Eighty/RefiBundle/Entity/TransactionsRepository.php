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
	public function filterSectors()
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT 
					  COUNT(t.id) as num_prospects,
					  t.sector,
					  pr.longitude AS pr_long,
					  pr.latitude AS pr_lat,
					  pr.name AS sector_name
					FROM RefiBundle:Prospectloan pl
					JOIN RefiBundle:Transactions t
						WITH t.id = pl.transactionId
					JOIN RefiBundle:Postalregion pr
						WITH t.sector = pr.regionCode
					LEFT JOIN RefiBundle:Sectorlist sl
						WITH sl.sectorCode = t.sector
					WHERE sl.sectorCode IS NULL
					GROUP BY t.sector
					ORDER BY t.sector ASC
				'
			)
			->getArrayResult();
	}
	
	public function filterProspectsBySector($switch = 0, $param)
	{
		if($switch == 0) {
			$where = "
				JOIN RefiBundle:Sectorlist sl
				WITH sl.sectorCode = t.sector
				WHERE sl.clientId = :param
			";
		} else {
			$where = "
				WHERE t.sector = :param
			";
		}
		
		return $this->getEntityManager()
			->createQuery(
				"SELECT 
					  t.urakey,
					  COUNT(t.id) AS average_assets_owned,
					  t.longitude,
					  t.latitude,
					  t.sector,
					  pr.longitude AS pr_long,
					  pr.latitude AS pr_lat,
					  pr.name AS sector_name,
					  round(AVG(t.price),2) AS average_price,
					  round(AVG(t.newprice),2) AS average_newprice,
					  round(AVG(p.age)) AS average_prospect_age,
					  round(AVG(p.derivedIncome),2) AS average_income,
					  round(AVG(pl.loanAmount),2) AS average_loan,
					  AVG(pl.ltv) AS average_ltv,
					  AVG(
						timestampdiff(YEAR, pl.loanDate, CURRENT_DATE())
					  ) AS average_loan_age,
					  pl.prospectId
					FROM RefiBundle:Prospectloan pl
					JOIN RefiBundle:Transactions t
						WITH t.id = pl.transactionId
					JOIN RefiBundle:Postalregion pr
						WITH t.sector = pr.regionCode
					JOIN RefiBundle:Prospect p
						WITH p.id = pl.prospectId
					$where
					GROUP BY pl.prospectId
					ORDER BY t.sector ASC
				"
			)
			->setParameter('param', $param)
			->getArrayResult();
	}
	
	public function fetchSectorsInListByClientId($id)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT sl.sectorCode
					FROM RefiBundle:Sectorlist sl
					WHERE sl.clientId = :cId
				'
			)
			->setParameter('cId', $id)
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