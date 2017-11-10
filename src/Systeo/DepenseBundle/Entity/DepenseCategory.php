<?php

namespace Systeo\DepenseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DepenseCategory
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="depense_category")
 * @ORM\Entity(repositoryClass="Systeo\DepenseBundle\Repository\DepenseCategoryRepository")
 * @ORM\HasLifecycleCallbacks()
 * use repository for handy tree functions
 * @ORM\Entity(repositoryClass="Gedmo\Tree\Entity\Repository\NestedTreeRepository")
 */
class DepenseCategory
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
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     */
    private $name;
    
    
    
    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(type="integer")
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(type="integer")
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(type="integer")
     */
    private $rgt;
    
    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="DepenseCategory")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $root;
    
    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="DepenseCategory", inversedBy="children")
     * @ORM\JoinColumn(referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="DepenseCategory", mappedBy="parent")
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;


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
     * @return DepenseCategory
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
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add child
     *
     * @param \Systeo\DepenseBundle\Entity\DepenseCategory $child
     *
     * @return DepenseCategory
     */
    public function addChild(\Systeo\DepenseBundle\Entity\DepenseCategory $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \Systeo\DepenseBundle\Entity\DepenseCategory $child
     */
    public function removeChild(\Systeo\DepenseBundle\Entity\DepenseCategory $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param \Systeo\DepenseBundle\Entity\DepenseCategory $parent
     *
     * @return DepenseCategory
     */
    public function setParent(\Systeo\DepenseBundle\Entity\DepenseCategory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Systeo\DepenseBundle\Entity\DepenseCategory
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return DepenseCategory
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return DepenseCategory
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return DepenseCategory
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param \Systeo\DepenseBundle\Entity\DepenseCategory $root
     *
     * @return DepenseCategory
     */
    public function setRoot(\Systeo\DepenseBundle\Entity\DepenseCategory $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return \Systeo\DepenseBundle\Entity\DepenseCategory
     */
    public function getRoot()
    {
        return $this->root;
    }
    
    /**
    * toString
    * @return string
    */
   public function __toString() 
   {
       return $this->getName();
   }
}
