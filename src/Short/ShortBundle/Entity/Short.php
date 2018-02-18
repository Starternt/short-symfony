<?php

namespace Short\ShortBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraint as Assert;

/**
 * @ORM\Entity(repositoryClass="ShortRepository")
 * @ORM\Table(name="url")
 * @ORM\HasLifecycleCallbacks()
 */
class Short
{

    /**
     * @ORM\id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=2046)
     */
    protected $original_url;

    /**
     * @ORM\Column(type="string", length=2046)
     */
    protected $short_url;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;

    /**
     * @ORM\Column(type="integer", nullable=true, options={"default" : 0})
     */
    protected $uses;

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
     * Set original_url
     *
     * @param string $originalUrl
     * @return Short
     */
    public function setOriginalUrl($originalUrl)
    {
        $this->original_url = $originalUrl;

        return $this;
    }

    /**
     * Get original_url
     *
     * @return string
     */
    public function getOriginalUrl()
    {
        return $this->original_url;
    }

    /**
     * Set short_url
     *
     * @param string $shortUrl
     * @return Short
     */
    public function setShortUrl($shortUrl)
    {
        $this->short_url = $shortUrl;

        return $this;
    }

    /**
     * Get short_url
     *
     * @return string
     */
    public function getShortUrl()
    {
        return $this->short_url;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Short
     * @ORM\PrePersist
     */
    public function setCreated($created)
    {
        $this->created = new \DateTime('now');

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
     * Set updated
     *
     * @param \DateTime $updated
     * @return Short
     * @ORM\PrePersist
     */
    public function setUpdated($updated)
    {
        $this->updated = new \DateTime('now');

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set uses
     *
     * @param integer $uses
     * @return Short
     */
    public function setUses($uses)
    {
        $this->uses = $uses;

        return $this;
    }

    /**
     * Get uses
     *
     * @return integer 
     */
    public function getUses()
    {
        return $this->uses;
    }
}
