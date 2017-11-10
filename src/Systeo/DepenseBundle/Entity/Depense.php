<?php

namespace Systeo\DepenseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Depense
 *
 * @ORM\Table(name="depense")
 * @ORM\Entity(repositoryClass="Systeo\DepenseBundle\Repository\DepenseRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Depense {

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
     * @ORM\ManyToOne(targetEntity="Systeo\DepenseBundle\Entity\DepenseCategory")
     * @ORM\JoinColumn(nullable=true)
     */
    private $depenseCategory;

    /**
     *
     * @ORM\ManyToOne(targetEntity="Systeo\TierBundle\Entity\Tier")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank(message="Vous devez sélectionner une tier!")
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
     * @var string
     *
     * @ORM\Column(name="remarque", type="string", length=255, nullable=true)
     */
    private $remarque;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_ht", type="decimal", precision=10, scale=3, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $montantHt;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_tva", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $montantTva;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_ttc", type="decimal", precision=10, scale=3, nullable=true)
     * @Assert\NotBlank(message="Ce champs est obligatoire!")
     */
    private $montantTtc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @ORM\OneToMany(targetEntity="Systeo\ReglementBundle\Entity\Reglement", mappedBy="depense")
     */
    private $reglements;

    /**
     * @var string
     *
     * @ORM\Column(name="solde", type="decimal", precision=10, scale=3, nullable=true)
     */
    private $solde;

    /**
     * Get id
     *
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Depense
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     *
     * @return Depense
     */
    public function setRemarque($remarque) {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string
     */
    public function getRemarque() {
        return $this->remarque;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Depense
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set depenseCategory
     *
     * @param \Systeo\DepenseBundle\Entity\DepenseCategory $depenseCategory
     *
     * @return Depense
     */
    public function setDepenseCategory(\Systeo\DepenseBundle\Entity\DepenseCategory $depenseCategory = null) {
        $this->depenseCategory = $depenseCategory;

        return $this;
    }

    /**
     * Get depenseCategory
     *
     * @return \Systeo\DepenseBundle\Entity\DepenseCategory
     */
    public function getDepenseCategory() {
        return $this->depenseCategory;
    }

    /**
     * Set tier
     *
     * @param \Systeo\TierBundle\Entity\Tier $tier
     *
     * @return Depense
     */
    public function setTier(\Systeo\TierBundle\Entity\Tier $tier = null) {
        $this->tier = $tier;

        return $this;
    }

    /**
     * Get tier
     *
     * @return \Systeo\TierBundle\Entity\Tier
     */
    public function getTier() {
        return $this->tier;
    }

    /**
     * Set montantHt
     *
     * @param string $montantHt
     *
     * @return Depense
     */
    public function setMontantHt($montantHt) {
        $this->montantHt = $montantHt;

        return $this;
    }

    /**
     * Get montantHt
     *
     * @return string
     */
    public function getMontantHt() {
        return $this->montantHt;
    }

    /**
     * Set montantTva
     *
     * @param string $montantTva
     *
     * @return Depense
     */
    public function setMontantTva($montantTva) {
        $this->montantTva = $montantTva;

        return $this;
    }

    /**
     * Get montantTva
     *
     * @return string
     */
    public function getMontantTva() {
        return $this->montantTva;
    }

    /**
     * Set montantTtc
     *
     * @param string $montantTtc
     *
     * @return Depense
     */
    public function setMontantTtc($montantTtc) {
        $this->montantTtc = $montantTtc;

        return $this;
    }

    /**
     * Get montantTtc
     *
     * @return string
     */
    public function getMontantTtc() {
        return $this->montantTtc;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->reglements = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add reglement
     *
     * @param \Systeo\ReglementBundle\Entity\Reglement $reglement
     *
     * @return Depense
     */
    public function addReglement(\Systeo\ReglementBundle\Entity\Reglement $reglement) {
        $this->reglements[] = $reglement;

        return $this;
    }

    /**
     * Remove reglement
     *
     * @param \Systeo\ReglementBundle\Entity\Reglement $reglement
     */
    public function removeReglement(\Systeo\ReglementBundle\Entity\Reglement $reglement) {
        $this->reglements->removeElement($reglement);
    }

    /**
     * Get reglements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReglements() {
        return $this->reglements;
    }

    /* public function getSolde() {
      $totalRegelement = 0;

      foreach ($this->getReglements() as $rg):
      $totalRegelement += $rg->getMontant();
      endforeach;

      return ($this->getMontantTtc() - $totalRegelement);
      } */

    /**
     * toString
     * @return string
     */
    public function __toString() {

        return 'N° : ' . $this->getId() .
                ' | Désignation : ' . $this->getName() .
                ' | Date : ' . $this->getDate()->format("d/m/Y") .
                ' | Montant TTC:' . number_format($this->getMontantTtc(), 3, '.', ' ') .
                ' | Solde:' . number_format($this->getSolde(), 3, '.', ' ');
    }

    /**
     * Set solde
     *
     * @param string $solde
     *
     * @return Depense
     */
    public function setSolde($solde) {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return string
     */
    public function getSolde() {
        return $this->solde;
    }

    /**
     * 
     * @return type
     */
    public function getCalculatedSolde() {

        $totalReglement = 0;

        foreach ($this->getReglements() as $reg):
            $totalReglement += $reg->getMontant();
        endforeach;

        return ($this->getMontantTtc() - $totalReglement);
    }
    
    /**
     * @ORM\PrePersist
     */
    public function setSoldeCreation(){
        
        $this->setSolde($this->montantTtc);
        return $this;
    }
    
    

}
