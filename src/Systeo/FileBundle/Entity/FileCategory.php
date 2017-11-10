<?php

namespace Systeo\FileBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FileCategory
 *
 * @ORM\Table(name="file_category")
 * @ORM\Entity(repositoryClass="Systeo\FileBundle\Repository\FileCategoryRepository")
 */
class FileCategory
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="Le nom de la catégorie est obligatoire")
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;
    
    private $search;


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
     * @return FileCategory
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
     * Set active
     *
     * @param boolean $active
     *
     * @return FileCategory
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return bool
     */
    public function getActive()
    {
        return $this->active;
    }
    
    /**
     * Set search
     *
     * @param string $search
     *
     * @return User
     */
    public function setSearch($search)
    {
        $this->search = $search;

        return $this;
    }

    /**
     * Get search
     *
     * @return string
     */
    public function getSearch()
    {
        return $this->search;
    }
}
