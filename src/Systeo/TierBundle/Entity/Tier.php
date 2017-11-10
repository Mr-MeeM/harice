<?php

namespace Systeo\TierBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Tier
 *
 * @ORM\Table(name="tier")
 * @ORM\Entity(repositoryClass="Systeo\TierBundle\Repository\TierRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Tier
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
     * @ORM\Column(name="type", type="string", length=1, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="rs", type="string", length=255, nullable=true)
     */
    private $rs;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=50, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=100, nullable=true)
     */
    private $lastName;
    

    /**
     * @var string
     *
     * @ORM\Column(name="tel1", type="string", length=20, nullable=true)
     */
    private $tel1;

    /**
     * @var string
     *
     * @ORM\Column(name="tel2", type="string", length=20, nullable=true)
     */
    private $tel2;

    /**
     * @var string
     *
     * @ORM\Column(name="fax1", type="string", length=20, nullable=true)
     */
    private $fax1;

    /**
     * @var string
     *
     * @ORM\Column(name="email1", type="string", length=100, nullable=true)
     */
    private $email1;

    /**
     * @var string
     *
     * @ORM\Column(name="email2", type="string", length=100, nullable=true)
     */
    private $email2;
   

    /**
     * @var string
     *
     * @ORM\Column(name="rue_numero1", type="string", length=100, nullable=true)
     */
    private $rueNumero1;
    
    
     /**
     * @var string
     *
     * @ORM\Column(name="cp1", type="string", length=255, nullable=true)
     */
    private $cp1;

    /**
     * @var string
     *
     * @ORM\Column(name="ville1", type="string", length=50, nullable=true)
     */
    private $ville1;

    /**
     * @var string
     *
     * @ORM\Column(name="pays1", type="string", length=50, nullable=true)
     */
    private $pays1;

    /**
     * @var bool
     *
     * @ORM\Column(name="fournisseur", type="boolean")
     */
    private $fournisseur;


    /**
     * @var bool
     *
     * @ORM\Column(name="client", type="boolean")
     */
    private $client;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="employe", type="boolean")
     */
    private $employe;
    
    


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
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modified", type="datetime")
     */
    private $modified;
    
    
    private $name;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Tier
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set rs
     *
     * @param string $rs
     *
     * @return Tier
     */
    public function setRs($rs)
    {
        $this->rs = $rs;

        return $this;
    }

    /**
     * Get rs
     *
     * @return string
     */
    public function getRs()
    {
        return $this->rs;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Tier
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Tier
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set tel1
     *
     * @param string $tel1
     *
     * @return Tier
     */
    public function setTel1($tel1)
    {
        $this->tel1 = $tel1;

        return $this;
    }

    /**
     * Get tel1
     *
     * @return string
     */
    public function getTel1()
    {
        return $this->tel1;
    }

    /**
     * Set tel2
     *
     * @param string $tel2
     *
     * @return Tier
     */
    public function setTel2($tel2)
    {
        $this->tel2 = $tel2;

        return $this;
    }

    /**
     * Get tel2
     *
     * @return string
     */
    public function getTel2()
    {
        return $this->tel2;
    }

    /**
     * Set fax1
     *
     * @param string $fax1
     *
     * @return Tier
     */
    public function setFax1($fax1)
    {
        $this->fax1 = $fax1;

        return $this;
    }

    /**
     * Get fax1
     *
     * @return string
     */
    public function getFax1()
    {
        return $this->fax1;
    }

    /**
     * Set email1
     *
     * @param string $email1
     *
     * @return Tier
     */
    public function setEmail1($email1)
    {
        $this->email1 = $email1;

        return $this;
    }

    /**
     * Get email1
     *
     * @return string
     */
    public function getEmail1()
    {
        return $this->email1;
    }

    /**
     * Set email2
     *
     * @param string $email2
     *
     * @return Tier
     */
    public function setEmail2($email2)
    {
        $this->email2 = $email2;

        return $this;
    }

    /**
     * Get email2
     *
     * @return string
     */
    public function getEmail2()
    {
        return $this->email2;
    }

    /**
     * Set rueNumero1
     *
     * @param string $rueNumero1
     *
     * @return Tier
     */
    public function setRueNumero1($rueNumero1)
    {
        $this->rueNumero1 = $rueNumero1;

        return $this;
    }

    /**
     * Get rueNumero1
     *
     * @return string
     */
    public function getRueNumero1()
    {
        return $this->rueNumero1;
    }

    /**
     * Set cp1
     *
     * @param string $cp1
     *
     * @return Tier
     */
    public function setCp1($cp1)
    {
        $this->cp1 = $cp1;

        return $this;
    }

    /**
     * Get cp1
     *
     * @return string
     */
    public function getCp1()
    {
        return $this->cp1;
    }

    /**
     * Set ville1
     *
     * @param string $ville1
     *
     * @return Tier
     */
    public function setVille1($ville1)
    {
        $this->ville1 = $ville1;

        return $this;
    }

    /**
     * Get ville1
     *
     * @return string
     */
    public function getVille1()
    {
        return $this->ville1;
    }

    /**
     * Set pays1
     *
     * @param string $pays1
     *
     * @return Tier
     */
    public function setPays1($pays1)
    {
        $this->pays1 = $pays1;

        return $this;
    }

    /**
     * Get pays1
     *
     * @return string
     */
    public function getPays1()
    {
        return $this->pays1;
    }

    /**
     * Set fournisseur
     *
     * @param boolean $fournisseur
     *
     * @return Tier
     */
    public function setFournisseur($fournisseur)
    {
        $this->fournisseur = $fournisseur;

        return $this;
    }

    /**
     * Get fournisseur
     *
     * @return boolean
     */
    public function getFournisseur()
    {
        return $this->fournisseur;
    }

    

    /**
     * Set client
     *
     * @param boolean $client
     *
     * @return Tier
     */
    public function setClient($client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return boolean
     */
    public function getClient()
    {
        return $this->client;
    }

    

    /**
     * Set mf
     *
     * @param string $mf
     *
     * @return Tier
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
     * @return Tier
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
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Tier
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     *
     * @return Tier
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Tier
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
        if($this->getType() === 'p'){
            return $this->getFirstName().' '.$this->getLastName();
        }
        
        return $this->getRs();
    }
    
    public function getTypeValues(){
        return [
            'm'=>'Personne morale',
            'p'=>'Personne physique',
        ];
    }
    
    /**
    * @ORM\PrePersist
    */
    public function setCreatedAt()
    {
        $this->setCreated(new \Datetime());
    }
    
    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setModifiedAt()
    {
        $this->setModified(new \Datetime());
    }
    
    

    /**
     * Set employe
     *
     * @param boolean $employe
     *
     * @return Tier
     */
    public function setEmploye($employe)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return boolean
     */
    public function getEmploye()
    {
        return $this->employe;
    }
    
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // check if the name is actually a fake name
        if ($this->getType() === 'm' && $this->getRs() == "") {
            $context->buildViolation('Veuillez saisir la raison sociale!')
                ->atPath('rs')
                ->addViolation();
        }elseif ($this->getType() === 'p') {
            if($this->getFirstName() == ""){
                $context->buildViolation('Ce champs est obligatoire!')
                    ->atPath('firstName')
                    ->addViolation();
            }
            if($this->getLastName() == ""){
                $context->buildViolation('Ce champs est obligatoire!')
                    ->atPath('lastName')
                    ->addViolation();
            }
            
        }
        
       
        
        
    }
}
