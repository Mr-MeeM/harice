<?php

namespace Systeo\VenteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PieceLigne
 *
 * @ORM\Table(name="piece_ligne")
 * @ORM\Entity(repositoryClass="Systeo\VenteBundle\Repository\PieceLigneRepository")
 */
class PieceLigne
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
     * @ORM\ManyToOne(targetEntity="Systeo\VenteBundle\Entity\Piece")
     * @ORM\JoinColumn(nullable=false)
     */
    private $piece;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=10)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="text")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="quantite", type="integer")
     */
    private $quantite;

    /**
     * @var string
     *
     * @ORM\Column(name="prix_ht", type="decimal", precision=10, scale=3)
     */
    private $prixHt;

    /**
     * @var string
     *
     * @ORM\Column(name="taux_tva", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $tauxTva;

    /**
     * @var string
     *
     * @ORM\Column(name="total_ht", type="decimal", precision=10, scale=3)
     */
    private $totalHt;


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
     * Set code
     *
     * @param string $code
     *
     * @return PieceLigne
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return PieceLigne
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
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return PieceLigne
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return int
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prixHt
     *
     * @param string $prixHt
     *
     * @return PieceLigne
     */
    public function setPrixHt($prixHt)
    {
        $this->prixHt = $prixHt;

        return $this;
    }

    /**
     * Get prixHt
     *
     * @return string
     */
    public function getPrixHt()
    {
        return $this->prixHt;
    }

    /**
     * Set tauxTva
     *
     * @param string $tauxTva
     *
     * @return PieceLigne
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

    /**
     * Set totalHt
     *
     * @param string $totalHt
     *
     * @return PieceLigne
     */
    public function setTotalHt($totalHt)
    {
        $this->totalHt = $totalHt;

        return $this;
    }

    /**
     * Get totalHt
     *
     * @return string
     */
    public function getTotalHt()
    {
        return $this->totalHt;
    }

    /**
     * Set piece
     *
     * @param \Systeo\VenteBundle\Entity\Piece $piece
     *
     * @return PieceLigne
     */
    public function setPiece(\Systeo\VenteBundle\Entity\Piece $piece)
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
     * 
     * @return boolean
     */
    public function checkIfValid(){
        if(
                $this->getCode() != '' &&
                $this->getName() != '' && 
                $this->getPrixHt() != '' && 
                $this->getQuantite() != '' && 
                $this->getTauxTva() != '' &&
                $this->getTotalHt() != '' 
        ){
            return true;
        }
        
        return false;
    }
}
