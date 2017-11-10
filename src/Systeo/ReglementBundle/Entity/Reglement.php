<?php

namespace Systeo\ReglementBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reglement
 *
 * @ORM\Table(name="reglement")
 * @ORM\Entity(repositoryClass="Systeo\ReglementBundle\Repository\ReglementRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Reglement
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
     *
     * @ORM\ManytoOne(targetEntity="Systeo\BanqueBundle\Entity\BanqueOperation", inversedBy="reglements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $banqueOperation;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Systeo\DepenseBundle\Entity\Depense", inversedBy="reglements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $depense;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Systeo\VenteBundle\Entity\Piece", inversedBy="reglements", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $piece;
    
     /**
     *
     * @ORM\ManyToOne(targetEntity="Systeo\TierBundle\Entity\Tier")
     * @ORM\JoinColumn(nullable=true)
     * 
     */
    private $tier;

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
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="montant", type="decimal", precision=10, scale=3, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="direction", type="string", length=3, nullable=true)
     */
    private $direction;
    
    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $type;


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
     * @return Reglement
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
     * @return Reglement
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
     * Set montant
     *
     * @param string $montant
     *
     * @return Reglement
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set direction
     *
     * @param string $direction
     *
     * @return Reglement
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * Set banqueOperation
     *
     * @param \Systeo\BanqueBundle\Entity\BanqueOperation $banqueOperation
     *
     * @return Reglement
     */
    public function setBanqueOperation(\Systeo\BanqueBundle\Entity\BanqueOperation $banqueOperation = null)
    {
        $this->banqueOperation = $banqueOperation;

        return $this;
    }

    /**
     * Get banqueOperation
     *
     * @return \Systeo\BanqueBundle\Entity\BanqueOperation
     */
    public function getBanqueOperation()
    {
        return $this->banqueOperation;
    }

    /**
     * Set depense
     *
     * @param \Systeo\DepenseBundle\Entity\Depense $depense
     *
     * @return Reglement
     */
    public function setDepense(\Systeo\DepenseBundle\Entity\Depense $depense = null)
    {
        $this->depense = $depense;

        return $this;
    }

    /**
     * Get depense
     *
     * @return \Systeo\DepenseBundle\Entity\Depense
     */
    public function getDepense()
    {
        return $this->depense;
    }

    /**
     * Set tier
     *
     * @param \Systeo\TierBundle\Entity\Tier $tier
     *
     * @return Reglement
     */
    public function setTier(\Systeo\TierBundle\Entity\Tier $tier = null)
    {
        $this->tier = $tier;

        return $this;
    }

    /**
     * Get tier
     *
     * @return \Systeo\TierBundle\Entity\Tier
     */
    public function getTier()
    {
        return $this->tier;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Reglement
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
    
    public function getTypeValues(){
        return [
            'Virement'=>'Virement',
            'Chèque'=>'Chèque',
            'Carte'=>'Carte',
            'Espèce'=>'Espèce',
            'Prélèvement'=>'Prélèvement',
            'Retenu à la source'=>'Retenu à la source'
        ];
    }
    
    /**
     * @ORM\PreUpdate
     * @ORM\PrePersist
     */
    public function setTierValue() {
        if(is_null($this->getTier()) && !is_null($this->getDepense())){
            $this->setTier($this->getDepense()->getTier());
        }elseif(is_null($this->getTier()) && !is_null($this->getPiece())){
            $this->setTier($this->getPiece()->getTier());
        }
    }
    
    

    /**
     * Set piece
     *
     * @param \Systeo\VenteBundle\Entity\Piece $piece
     *
     * @return Reglement
     */
    public function setPiece(\Systeo\VenteBundle\Entity\Piece $piece = null)
    {
        $this->piece = $piece;

        return $this;
    }

    /**
     * Get piece
     *
     * @return \Systeo\VenteBundle\Entity\Piece
     */
    public function getPiece()
    {
        return $this->piece;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function SetPieceDepenseSoldeCreation(){
        
        if($this->direction === "in"){
            if(!is_null($this->getPiece())){
                $this->getPiece()->setSolde($this->getPiece()->getCalculatedSolde() - $this->getMontant());
            }
            if(!is_null($this->getBanqueOperation())){
                $this->getBanqueOperation()->setSoldeReglementCredit($this->getBanqueOperation()->getSoldeReglement() - $this->getMontant());
            }
            
        }elseif($this->direction === "out"){
            if(!is_null($this->getDepense())){
                $this->getDepense()->setSolde($this->getDepense()->getCalculatedSolde() - $this->getMontant());
            }
            if(!is_null($this->getBanqueOperation())){
                $this->getBanqueOperation()->setSoldeReglementDebit($this->getBanqueOperation()->getSoldeReglement() - $this->getMontant());
            }
        }
        
    }
    
    //Pas de preUpdate , ça ne fonctionne pas avec Doctrie pour les entités liées
    
    /**
     * @ORM\PreRemove
     */
    public function SetPieceDepenseSoldeRemove(){
        
        if($this->direction === "in"){
            if(!is_null($this->getPiece())){
                $this->getPiece()->setSolde($this->getPiece()->getCalculatedSolde() + $this->getMontant());
            }
            if(!is_null($this->getBanqueOperation())){
                $this->getBanqueOperation()->setSoldeReglementCredit($this->getBanqueOperation()->getSoldeReglement() + $this->getMontant());
            }
            
        }elseif($this->direction === "out"){
            if(!is_null($this->getDepense())){
                $this->getDepense()->setSolde($this->getDepense()->getCalculatedSolde() + $this->getMontant());
            }
            if(!is_null($this->getBanqueOperation())){
                $this->getBanqueOperation()->setSoldeReglementDebit($this->getBanqueOperation()->getSoldeReglement() + $this->getMontant());
            }
        }
        
    }
    
    
}
