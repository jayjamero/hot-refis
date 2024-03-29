<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ReportlistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReportlistRepository extends EntityRepository
{
	public function getReportListContactedEngaged($id)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT pr.name AS sector_name,
						rl.transactionId,
						rl.status,
						rl.note,
						pr.regionCode,
						pl.prospectId,
						rl.fullname,
						rl.email,
						rl.mobilenumber,
						rl.id
					FROM RefiBundle:Reportlist rl
					JOIN RefiBundle:Transactions tr
						WITH tr.id = rl.transactionId
					JOIN RefiBundle:Postalregion pr
						WITH pr.regionCode = tr.sector
					JOIN RefiBundle:Prospectloan pl
						WITH pl.transactionId = tr.id
					WHERE rl.clientId = :uId
					AND rl.status > 0
				'
			)
			->setParameter('uId', $id)
			->getArrayResult();
	}

}