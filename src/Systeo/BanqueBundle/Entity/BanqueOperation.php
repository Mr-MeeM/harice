<?php

namespace Systeo\BanqueBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * BanqueOperation
 *
 * @ORM\Table(name="banque_operation")
 * @ORM\Entity(repositoryClass="Systeo\BanqueBundle\Repository\BanqueOperationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BanqueOperation
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
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_valeur", type="date", nullable=true)
     */
    private $dateValeur;

    /**
     * @var string
     *
     * @ORM\Column(name="debit", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $debit;

    /**
     * @var string
     *
     * @ORM\Column(name="credit", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $credit;

    /**
     * @var string
     *
     * @ORM\Column(name="solde", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $solde;
    
    /**
     * @var string
     *
     * @ORM\Column(name="solde_reglement_debit", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $soldeReglementDebit;
    
    /**
     * @var string
     *
     * @ORM\Column(name="solde_reglement_credit", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $soldeReglementCredit;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Systeo\BanqueBundle\Entity\BanqueCompte")
     * @Assert\NotBlank(message="Veuillez choisir un compte")
     * @ORM\JoinColumn(nullable=false)
     */
    private $banqueCompte;
    
    /**
    * @ORM\OneToMany(targetEntity="Systeo\ReglementBundle\Entity\Reglement", mappedBy="banqueOperation")
    */
    private $reglements;


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
     * Set name
     *
     * @param string $name
     *
     * @return BanqueOperation
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

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return BanqueOperation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set dateValeur
     *
     * @param \DateTime $dateValeur
     *
     * @return BanqueOperation
     */
    public function setDateValeur($dateValeur)
    {
        $this->dateValeur = $dateValeur;

        return $this;
    }

    /**
     * Get dateValeur
     *
     * @return \DateTime
     */
    public function getDateValeur()
    {
        return $this->dateValeur;
    }

    /**
     * Set debit
     *
     * @param string $debit
     *
     * @return BanqueOperation
     */
    public function setDebit($debit)
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     * Get debit
     *
     * @return string
     */
    public function getDebit()
    {
        return $this->debit;
    }

    /**
     * Set credit
     *
     * @param string $credit
     *
     * @return BanqueOperation
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;

        return $this;
    }

    /**
     * Get credit
     *
     * @return string
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Set solde
     *
     * @param string $solde
     *
     * @return BanqueOperation
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return string
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set banqueCompte
     *
     * @param \Systeo\BanqueBundle\Entity\BanqueCompte $banqueCompte
     *
     * @return BanqueOperation
     */
    public function setBanqueCompte(\Systeo\BanqueBundle\Entity\BanqueCompte $banqueCompte)
    {
        $this->banqueCompte = $banqueCompte;

        return $this;
    }

    /**
     * Get banqueCompte
     *
     * @return \Systeo\BanqueBundle\Entity\BanqueCompte
     */
    public function getBanqueCompte()
    {
        return $this->banqueCompte;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reglements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reglement
     *
     * @param \Systeo\ReglementBundle\Entity\Reglement $reglement
     *
     * @return BanqueOperation
     */
    public function addReglement(\Systeo\ReglementBundle\Entity\Reglement $reglement)
    {
        $this->reglements[] = $reglement;

        return $this;
    }

    /**
     * Remove reglement
     *
     * @param \Systeo\ReglementBundle\Entity\Reglement $reglement
     */
    public function removeReglement(\Systeo\ReglementBundle\Entity\Reglement $reglement)
    {
        $this->reglements->removeElement($reglement);
    }

    /**
     * Get reglements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReglements()
    {
        return $this->reglements;
    }
    
    public function getTypeThroughName(){
        
        $name = $this->getName();
        
        if(stripos($name, 'cheque')){
            return "Chèque";
        }
        
        if(stripos($name, 'vir')){
            return "Virement";
        }
        
        if(stripos($name, 'carte')){
            return "Carte";
        }
        
        return "Espèce";
        
    }

    /**
     * Set soldeReglement
     *
     * @param string $soldeReglement
     *
     * @return BanqueOperation
     */
    public function setSoldeReglement($soldeReglement)
    {
        $this->soldeReglement = $soldeReglement;

        return $this;
    }

    /**
     * Get soldeReglement
     *
     * @return string
     */
    public function getSoldeReglement()
    {
        $totalRegelement = 0;
        
        foreach($this->getReglements() as $rg):
            $totalRegelement += $rg->getMontant();
        endforeach;
        
        if((int)$this->getDebit()!==0){
            return ($this->getDebit() - $totalRegelement);
        }
        
        return ($this->getCredit() - $totalRegelement);
        
        
    }

    /**
     * Set soldeReglementDebit
     *
     * @param string $soldeReglementDebit
     *
     * @return BanqueOperation
     */
    public function setSoldeReglementDebit($soldeReglementDebit)
    {
        $this->soldeReglementDebit = $soldeReglementDebit;

        return $this;
    }

    /**
     * Get soldeReglementDebit
     *
     * @return string
     */
    public function getSoldeReglementDebit()
    {
        return $this->soldeReglementDebit;
    }

    /**
     * Set soldeReglementCredit
     *
     * @param string $soldeReglementCredit
     *
     * @return BanqueOperation
     */
    public function setSoldeReglementCredit($soldeReglementCredit)
    {
        $this->soldeReglementCredit = $soldeReglementCredit;

        return $this;
    }

    /**
     * Get soldeReglementCredit
     *
     * @return string
     */
    public function getSoldeReglementCredit()
    {
        return $this->soldeReglementCredit;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setSoldeCreation(){
        
        if((int)$this->getDebit()!==0){
            $this->setSoldeReglementDebit($this->getDebit());
        }else{
            $this->setSoldeReglementCredit($this->getCredit());
        }
        return $this;
    }
    
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        if(!empty($this->getCredit()) && !empty($this->getDebit())){
            $context->buildViolation("Erreur! Veuillez remplir l'un des champs débit ou crédit mais pas les deux à la fois.")
                ->atPath('credit')
                ->addViolation();
        }if(empty($this->getCredit()) && empty($this->getDebit())){
            $context->buildViolation("Erreur! Veuillez remplir l'un des champs débit ou crédit.")
                ->atPath('credit')
                ->addViolation();
        }

        if ($this->getBanqueCompte()->getId() != 1 && empty($this->getDateValeur())) {
            $context->buildViolation('Ce champs est obligatoire')
                ->atPath('dateValeur')
                ->addViolation();
        }
        
       
        
        
    }
    
    
}
