<?php

namespace Systeo\BanqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BanqueCompte
 *
 * @ORM\Table(name="banque_compte")
 * @ORM\Entity(repositoryClass="Systeo\BanqueBundle\Repository\BanqueCompteRepository")
 */
class BanqueCompte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=30)
     * @Assert\NotBlank(message="Veuillez saisir un nom")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="rib", type="string", length=30)
     * @Assert\NotBlank(message="Veuillez saisir un rib")
     */
    private $rib;
    
    

    /**
     * @var string
     *
     * @ORM\Column(name="swift", type="string", length=20, nullable=true)
     */
    private $swift;

    /**
     * @var string
     *
     * @ORM\Column(name="iban", type="string", length=255, nullable=true)
     */
    private $iban;

    /**
     * @var string
     *
     * @ORM\Column(name="banque", type="string", length=20, nullable=true)
     * @Assert\NotBlank(message="Veuillez saisir une banque")
     */
    private $banque;



    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set rib
     *
     * @param string $rib
     *
     * @return BanqueCompte
     */
    public function setRib($rib)
    {
        $this->rib = $rib;

        return $this;
    }

    /**
     * Get rib
     *
     * @return string
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set swift
     *
     * @param string $swift
     *
     * @return BanqueCompte
     */
    public function setSwift($swift)
    {
        $this->swift = $swift;

        return $this;
    }

    /**
     * Get swift
     *
     * @return string
     */
    public function getSwift()
    {
        return $this->swift;
    }

    /**
     * Set iban
     *
     * @param string $iban
     *
     * @return BanqueCompte
     */
    public function setIban($iban)
    {
        $this->iban = $iban;

        return $this;
    }

    /**
     * Get iban
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Set banque
     *
     * @param string $banque
     *
     * @return BanqueCompte
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return string
     */
    public function getBanque()
    {
        return $this->banque;
    }


    /**
     * Set name
     *
     * @param string $name
     *
     * @return BanqueCompte
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
}
