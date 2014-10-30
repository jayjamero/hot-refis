<?php

namespace Eighty\RefiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Client
 *
 * @ORM\Table(name="client")
 * @ORM\Entity
 */
class Client implements AdvancedUserInterface, \Serializable
{
    /**
     * @var string
     *
     * @ORM\Column(name="fullname", type="string", length=255, nullable=true)
     */
    private $fullname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=400, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="agency", type="string", length=255, nullable=true)
     */
    private $agency;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=40, nullable=true)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=64, nullable=false)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=32, nullable=false)
     */
    private $salt;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=500, nullable=true)
     */
    private $address;

    /**
     * @var boolean
     *
     * @ORM\Column(name="agree_terms", type="boolean", nullable=true)
     */
    private $agreeTerms;

    /**
     * @var integer
     *
     * @ORM\Column(name="valid", type="integer", nullable=true)
     */
    private $valid;

    /**
     * @var integer
     *
     * @ORM\Column(name="packageid", type="bigint", nullable=true)
     */
    private $packageid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=true)
     */
    private $expiryDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;


	public function __construct()
    {
		$this->login = false;
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
    }
	
    /**
     * Set fullname
     *
     * @param string $fullname
     * @return Client
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;

        return $this;
    }

    /**
     * Get fullname
     *
     * @return string 
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Client
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set agency
     *
     * @param string $agency
     * @return Client
     */
    public function setAgency($agency)
    {
        $this->agency = $agency;

        return $this;
    }

    /**
     * Get agency
     *
     * @return string 
     */
    public function getAgency()
    {
        return $this->agency;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return Client
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return Client
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Client
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Client
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set agreeTerms
     *
     * @param boolean $agreeTerms
     * @return Client
     */
    public function setAgreeTerms($agreeTerms)
    {
        $this->agreeTerms = $agreeTerms;

        return $this;
    }

    /**
     * Get agreeTerms
     *
     * @return boolean 
     */
    public function getAgreeTerms()
    {
        return $this->agreeTerms;
    }

    /**
     * Set valid
     *
     * @param integer $valid
     * @return Client
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return integer 
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set packageid
     *
     * @param integer $packageid
     * @return Client
     */
    public function setPackageid($packageid)
    {
        $this->packageid = $packageid;

        return $this;
    }

    /**
     * Get packageid
     *
     * @return integer 
     */
    public function getPackageid()
    {
        return $this->packageid;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     * @return Client
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime 
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	
	
	// for AdvanceUserInterface
    public function getRoles()
    {
        return array('ROLE_USER');
	}

	public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        //return $this->login;
		return true;
    }

    // for UserInterface extended by AdvanceUserInterface
    public function getUsername()
    {
        return $this->getEmail();
    }

    public function eraseCredentials()
    {

    }
    
    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,

        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized);
    }
}