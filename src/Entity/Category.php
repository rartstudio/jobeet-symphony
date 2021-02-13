<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 * @ORM\Table(name="categories")
 */

class Category 
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * 
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var Job[]|ArrayCollection
     * 
     * @ORM\OneToMany(targetEntity="Job", mappedBy="category")
     */
    private $jobs;


    /**
     * @var Affiliates[]|ArrayCollection
     * 
     * @ORM\ManyToMany(targetEntity="Affiliate", mappedBy="categories")
     */
    private $affiliates;

    /**
     * @var string
     * 
     * @Gedmo\Slug(fields={"name"})
     * 
     * @ORM\Column(type="string", length=128, unique=true)
     */
    private $slug;

    public function __construct()
    {
        $this->jobs= new ArrayCollection();
        $this->affiliates = new ArrayCollection();
    }

    /**
     * Get Data From Id
     *
     * @return integer|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get Name Of Categories
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set Name Of Categories
     *
     * @param string $name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;        
    }

    /**
     * Get Jobs
     *
     * @return void
     */
    public function getJobs()
    {
        $this->jobs;
    }

    /**
     * Adding Job
     *
     * @param Job $job
     * @return void
     */
    public function addJob(Job $job): self
    {
        if(!$this->jobs->contains($job)){
            $this->jobs->add($job);
        }
        return $this;
    }

    /**
     * Remove Job
     *
     * @param Job $job
     * @return self
     */
    public function removeJob(Job $job): self
    {
        $this->jobs->removeElement($job);
        return $this;
    }

    /**
     * Get Affiliates
     *
     * @return void
     */
    public function getAffiliates()
    {
        return $this->affiliates;
    }

    /**
     * Add affiliate
     *
     * @param Affiliate $affiliate
     * @return self
     */
    public function addAffiliate($affiliate): self
    {
        if(!$this->affiliates->contains($affiliate)){
            $this->affiliates->add($affiliate);
        }

        return $this;
    }

    public function removeAffiliate($affiliate): self
    {
        $this->affiliates->removeElement($affiliate);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return void
     */
    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getActiveJobs()
    {
        return $this->jobs->filter(function(Job $job){
            return $job->getExpiresAt() > new \DateTime() && $job->isActivated();
        });
    }
}