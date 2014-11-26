<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ProspectlistRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProspectlistRepository extends EntityRepository
{
	public function getProspectList($id)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT pr.name AS sector_name,
						pl.prospectId,
						p.profession,
						p.derivedIncome,
						COUNT(plo.transactionId) AS property_owned,
						pl.status,
						pl.note
					FROM RefiBundle:Prospectlist pl
					JOIN RefiBundle:Prospect p
						WITH pl.prospectId = p.id
					JOIN RefiBundle:Prospectloan plo
						WITH pl.prospectId = plo.prospectId
					JOIN RefiBundle:Sectorlist sl
						WITH sl.id = pl.sectorlistId
					JOIN RefiBundle:Postalregion pr
						WITH pr.regionCode = sl.sectorCode
					WHERE sl.clientId = :uId
					GROUP BY pl.prospectId
				'
			)
			->setParameter('uId', $id)
			->getArrayResult();
	}
	
	public function getProspectListContactedEngaged($id)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT pr.name AS sector_name,
						pl.prospectId,
						p.profession,
						p.derivedIncome,
						COUNT(plo.transactionId) AS property_owned,
						pl.status,
						pl.note
					FROM RefiBundle:Prospectlist pl
					JOIN RefiBundle:Prospect p
						WITH pl.prospectId = p.id
					JOIN RefiBundle:Prospectloan plo
						WITH pl.prospectId = plo.prospectId
					JOIN RefiBundle:Sectorlist sl
						WITH sl.id = pl.sectorlistId
					JOIN RefiBundle:Postalregion pr
						WITH pr.regionCode = sl.sectorCode
					WHERE sl.clientId = :uId
					AND pl.status > 0
					GROUP BY pl.prospectId
				'
			)
			->setParameter('uId', $id)
			->getArrayResult();
	}

}