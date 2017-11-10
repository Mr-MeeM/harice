<?php

namespace Systeo\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * File
 *
 * @ORM\Table(name="file")
 * @ORM\Entity(repositoryClass="Systeo\FileBundle\Repository\FileRepository")
 */
class File
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="file_name", type="string", length=255)
     */
    private $fileName;

    /**
     * @var string
     *
     * @ORM\Column(name="entity", type="string", length=255, nullable=true)
     */
    private $entity;

    /**
     * @var int
     *
     * @ORM\Column(name="entity_id", type="integer", nullable=true)
     */
    private $entityId;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    private $path;
    
    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5, nullable=true)
     */
    private $extension;
    
    /**
     * @var bool
     *
     * @ORM\Column(name="image", type="boolean")
     */
    private $image;
    
    /**
    * @ORM\ManyToMany(targetEntity="Systeo\FileBundle\Entity\FileCategory", cascade={"persist"})
    */
    private $filecategories;


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
     * @return File
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
     * Set fileName
     *
     * @param string $fileName
     *
     * @return File
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set entity
     *
     * @param string $entity
     *
     * @return File
     */
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get entity
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set entityId
     *
     * @param integer $entityId
     *
     * @return File
     */
    public function setEntityId($entityId)
    {
        $this->entityId = $entityId;

        return $this;
    }

    /**
     * Get entityId
     *
     * @return int
     */
    public function getEntityId()
    {
        return $this->entityId;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        if(!$this->path){
            $path = $this->getEntity().'/'.$this->getEntityId();
            
            if($path !== '/'){
                return $path;
            }
        }
        return $this->path;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->filecategories = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return File
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set image
     *
     * @param boolean $image
     *
     * @return File
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return boolean
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Add filecategory
     *
     * @param \Systeo\FileBundle\Entity\FileCategory $filecategory
     *
     * @return File
     */
    public function addFilecategory(\Systeo\FileBundle\Entity\FileCategory $filecategory)
    {
        $this->filecategories[] = $filecategory;

        return $this;
    }

    /**
     * Remove filecategory
     *
     * @param \Systeo\FileBundle\Entity\FileCategory $filecategory
     */
    public function removeFilecategory(\Systeo\FileBundle\Entity\FileCategory $filecategory)
    {
        $this->filecategories->removeElement($filecategory);
    }

    /**
     * Get filecategories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFilecategories()
    {
        return $this->filecategories;
    }
}
