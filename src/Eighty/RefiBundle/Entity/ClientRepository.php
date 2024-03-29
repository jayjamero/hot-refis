<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ClientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ClientRepository extends EntityRepository
{
	public function registerUser($postdata)
	{
		//var_dump($postdata); exit();
		$this->getEntityManager()->getConnection()
            ->prepare("INSERT INTO 
							client  (
								fullname,
								email,
								phone,
								password,
								salt
							) 
						VALUES (
							'$postdata[fullname]',
							'$postdata[email]',
							'$postdata[phone]',
							'$postdata[password]',
							'$postdata[salt]'
						)"
					)
			->execute();
	}
	
	public function getRemainingCreditsById($id)
	{
		$package_credits = $this->getPackageCreditsById($id);
		$credits_used = $this->getCreditsUsedById($id);
		
		return $package_credits[0]['noCredits'] - (isset($credits_used[0]) ? $credits_used[0]['sum_credit_used'] : 0);
	}
	
	public function getPackageCreditsById($id)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT 
					  p.noCredits 
					FROM
					  RefiBundle:Package p
					JOIN RefiBundle:Contract c
					WITH p.id = c.packageId 
					WHERE c.clientId = :cId
				'
			)
			->setParameter('cId', $id)
			->getArrayResult();
	}
	
	public function getCreditsUsedById($id)
	{
		return $this->getEntityManager()
			->createQuery(
				'SELECT 
					  SUM(c.creditUsed) as sum_credit_used
					FROM
					  RefiBundle:Creditused c
					WHERE c.clientId = :cId
				'
			)
			->setParameter('cId', $id)
			->getArrayResult();
	}
}