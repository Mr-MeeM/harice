<?php

namespace Systeo\ConfigBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="Systeo\ConfigBundle\Repository\ConfigRepository")
 */
class Config
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
     * @ORM\Column(name="logo", type="string", length=50, nullable=true)
     */
    private $logo;

    /**
     * @var string
     *
     * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
     */
    private $companyName;

    /**
     * @var string
     *
     * @ORM\Column(name="taux_tva", type="string", length=255, nullable=true)
     */
    private $tauxTva;
    
    /**
     * @var string
     *
     * @ORM\Column(name="droit_timbre",  type="decimal", precision=10, scale=3, nullable=true)
     */
    private $droitTimbre;
    
    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=20, nullable=true)
     */
    private $tel;
    
    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=20, nullable=true)
     */
    private $fax;
    
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=100, nullable=true)
     */
    private $email;
    
    /**
     * @var string
     *
     * @ORM\Column(name="web", type="string", length=100, nullable=true)
     */
    private $web;
    
    /**
     * @var string
     *
     * @ORM\Column(name="mf", type="string", length=20, nullable=true)
     */
    private $mf;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rc", type="string", length=20, nullable=true)
     */
    private $rc;
    
    /**
     * @var string
     *
     * @ORM\Column(name="cd", type="string", length=20, nullable=true)
     */
    private $cd;
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;
    
    /**
     * @var string
     *
     * @ORM\Column(name="banque", type="string", length=50, nullable=true)
     */
    private $banque;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rib", type="string", length=30, nullable=true)
     */
    private $rib;
    
    /**
     * @var string
     *
     * @ORM\Column(name="swift", type="string", length=30, nullable=true)
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
     * @ORM\Column(name="couleur1", type="string", length=10, nullable=true)
     */
    private $couleur1;
    
    /**
     * @var float
     *
     * @ORM\Column(name="fodec", type="float", nullable=true)
     */
    private $fodec;


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
     * Set logo
     *
     * @param string $logo
     *
     * @return Config
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Config
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set tauxTva
     *
     * @param string $tauxTva
     *
     * @return Config
     */
    public function setTauxTva($tauxTva)
    {
        $this->tauxTva = $tauxTva;

        return $this;
    }

    /**
     * Get tauxTva
     *
     * @return string
     */
    public function getTauxTva()
    {
        return $this->tauxTva;
    }
    
    public function getTvaValues(){
        
        $tab = explode(';',$this->getTauxTva());
        
        $result = [];
        
        if(!empty($tab)){
            foreach($tab as $v):
                $result[$v] = $v.'%';
            endforeach;
        }
        
        return $result;
        
        
    }

    /**
     * Set droitTimbre
     *
     * @param string $droitTimbre
     *
     * @return Config
     */
    public function setDroitTimbre($droitTimbre)
    {
        $this->droitTimbre = $droitTimbre;

        return $this;
    }

    /**
     * Get droitTimbre
     *
     * @return string
     */
    public function getDroitTimbre()
    {
        return $this->droitTimbre;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Config
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Config
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Config
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
     * Set web
     *
     * @param string $web
     *
     * @return Config
     */
    public function setWeb($web)
    {
        $this->web = $web;

        return $this;
    }

    /**
     * Get web
     *
     * @return string
     */
    public function getWeb()
    {
        return $this->web;
    }

    /**
     * Set mf
     *
     * @param string $mf
     *
     * @return Config
     */
    public function setMf($mf)
    {
        $this->mf = $mf;

        return $this;
    }

    /**
     * Get mf
     *
     * @return string
     */
    public function getMf()
    {
        return $this->mf;
    }

    /**
     * Set rc
     *
     * @param string $rc
     *
     * @return Config
     */
    public function setRc($rc)
    {
        $this->rc = $rc;

        return $this;
    }

    /**
     * Get rc
     *
     * @return string
     */
    public function getRc()
    {
        return $this->rc;
    }

    /**
     * Set cd
     *
     * @param string $cd
     *
     * @return Config
     */
    public function setCd($cd)
    {
        $this->cd = $cd;

        return $this;
    }

    /**
     * Get cd
     *
     * @return string
     */
    public function getCd()
    {
        return $this->cd;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Config
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set banque
     *
     * @param string $banque
     *
     * @return Config
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
     * Set rib
     *
     * @param string $rib
     *
     * @return Config
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
     * @return Config
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
     * @return Config
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
     * Set couleur1
     *
     * @param string $couleur1
     *
     * @return Config
     */
    public function setCouleur1($couleur1)
    {
        $this->couleur1 = $couleur1;

        return $this;
    }

    /**
     * Get couleur1
     *
     * @return string
     */
    public function getCouleur1()
    {
        return $this->couleur1;
    }

    /**
     * Set fodec
     *
     * @param float $fodec
     *
     * @return Config
     */
    public function setFodec($fodec)
    {
        $this->fodec = $fodec;

        return $this;
    }

    /**
     * Get fodec
     *
     * @return float
     */
    public function getFodec()
    {
        return $this->fodec;
    }
}
