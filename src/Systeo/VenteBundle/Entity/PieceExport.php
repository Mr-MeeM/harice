<?php

namespace Systeo\VenteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Piece
 *
 * @ORM\Table(name="piece_export")
 * @ORM\Entity(repositoryClass="Systeo\VenteBundle\Repository\PieceExportRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class PieceExport
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
     * @ORM\ManyToOne(targetEntity="Systeo\TierBundle\Entity\Tier")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank(message="Vous devez sélectionner une tier!")
     */
    private $tier;
    
    /**
     *
     * @ORM\ManyToOne(targetEntity="Systeo\VenteBundle\Entity\Piece")
     * @ORM\JoinColumn(nullable=true)
     */
    private $piece;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10)
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="tier_name", type="string", length=255, nullable=true)
     */
    private $tierName;
    
    /**
     * @var string
     *
     * @ORM\Column(name="devise", type="string", length=2, nullable=true)
     *  @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $devise;

    /**
     * @var string
     *
     * @ORM\Column(name="tier_adresse", type="string", length=255, nullable=true)
     */
    private $tierAdresse;

    /**
     * @var string
     *
     * @ORM\Column(name="tier_mf", type="string", length=30, nullable=true)
     */
    private $tierMf;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_ht", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $montantHt;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_tva", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $montantTva;
    

    /**
     * @var string
     *
     * @ORM\Column(name="montant_ttc", type="decimal", precision=10, scale=2, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $montantTtc;
    
    /**
    * @ORM\OneToMany(targetEntity="Systeo\VenteBundle\Entity\PieceExportLigne", cascade={"persist","remove"}, mappedBy="pieceExport")
    */
    private $pieceExportLignes;
    
    /**
     * @var bool
     *
     */
    private $lignesAreValid;
    


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
     * Set type
     *
     * @param string $type
     *
     * @return Piece
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Piece
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
     * Set numero
     *
     * @param integer $numero
     *
     * @return Piece
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set tierName
     *
     * @param string $tierName
     *
     * @return Piece
     */
    public function setTierName($tierName)
    {
        $this->tierName = $tierName;

        return $this;
    }

    /**
     * Get tierName
     *
     * @return string
     */
    public function getTierName()
    {
        return $this->tierName;
    }

    /**
     * Set tierAdresse
     *
     * @param string $tierAdresse
     *
     * @return Piece
     */
    public function setTierAdresse($tierAdresse)
    {
        $this->tierAdresse = $tierAdresse;

        return $this;
    }

    /**
     * Get tierAdresse
     *
     * @return string
     */
    public function getTierAdresse()
    {
        return $this->tierAdresse;
    }

    /**
     * Set tierMf
     *
     * @param string $tierMf
     *
     * @return Piece
     */
    public function setTierMf($tierMf)
    {
        $this->tierMf = $tierMf;

        return $this;
    }

    /**
     * Get tierMf
     *
     * @return string
     */
    public function getTierMf()
    {
        return $this->tierMf;
    }

    /**
     * Set montantHt
     *
     * @param string $montantHt
     *
     * @return Piece
     */
    public function setMontantHt($montantHt)
    {
        $this->montantHt = $montantHt;

        return $this;
    }

    /**
     * Get montantHt
     *
     * @return string
     */
    public function getMontantHt()
    {
        return $this->montantHt;
    }

    /**
     * Set montantTva
     *
     * @param string $montantTva
     *
     * @return Piece
     */
    public function setMontantTva($montantTva)
    {
        $this->montantTva = $montantTva;

        return $this;
    }

    /**
     * Get montantTva
     *
     * @return string
     */
    public function getMontantTva()
    {
        return $this->montantTva;
    }

    /**
     * Set montantTtc
     *
     * @param string $montantTtc
     *
     * @return Piece
     */
    public function setMontantTtc($montantTtc)
    {
        $this->montantTtc = $montantTtc;

        return $this;
    }

    /**
     * Get montantTtc
     *
     * @return string
     */
    public function getMontantTtc()
    {
        return $this->montantTtc;
    }

    /**
     * Set tier
     *
     * @param \Systeo\TierBundle\Entity\Tier $tier
     *
     * @return Piece
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
     * Constructor
     */
    public function __construct()
    {
        $this->pieceLignes = new \Doctrine\Common\Collections\ArrayCollection();
    }

   


    /**
     * Set devise
     *
     * @param string $devise
     *
     * @return PieceExport
     */
    public function setDevise($devise)
    {
        $this->devise = $devise;

        return $this;
    }

    /**
     * Get devise
     *
     * @return string
     */
    public function getDevise()
    {
        return $this->devise;
    }

   

    /**
     * Add pieceExportLigne
     *
     * @param \Systeo\VenteBundle\Entity\PieceExportLigne $pieceExportLigne
     *
     * @return PieceExport
     */
    public function addPieceExportLigne(\Systeo\VenteBundle\Entity\PieceExportLigne $pieceExportLigne)
    {
        $pieceExportLigne->setPieceExport($this);
        $this->pieceExportLignes[] = $pieceExportLigne;

        return $this;
    }

    /**
     * Remove pieceExportLigne
     *
     * @param \Systeo\VenteBundle\Entity\PieceExportLigne $pieceExportLigne
     */
    public function removePieceExportLigne(\Systeo\VenteBundle\Entity\PieceExportLigne $pieceExportLigne)
    {
        $this->pieceExportLignes->removeElement($pieceExportLigne);
    }

    /**
     * Get pieceExportLignes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPieceExportLignes()
    {
        return $this->pieceExportLignes;
    }
    
    /**
     * 
     * @return type
     */
    public function getSolde(){
        return $this->getMontantTtc();
    }
    
    
    public function getreglements(){
        return [];
    }

    /**
     * Set piece
     *
     * @param \Systeo\VenteBundle\Entity\Piece $piece
     *
     * @return PieceExport
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
     * Set lignesAreValid
     *
     * @param boolean $lignesAreValid
     *
     * @return Piece
     */
    public function setLignesAreValid($lignesAreValid)
    {
        $this->lignesAreValid = $lignesAreValid;

        return $this;
    }

    /**
     * Get lignesAreValid
     *
     * @return boolean
     */
    public function getLignesAreValid()
    {
        foreach($this->getPieceExportLignes() as $ligne){
            if(!$ligne->checkIfValid()){
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context, $payload)
    {
        // check if the name is actually a fake name
        if (!$this->getLignesAreValid()) {
            $context->buildViolation('Tous les champs de toutes les lignes doivent être valides!')
                ->atPath('lignesAreValid')
                ->addViolation();
        }
        
       
        
        
    }
}
