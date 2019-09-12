<?php

namespace RvltDigital\SymfonyRevoltaBundle\Traits;

use Doctrine\ORM\Mapping as ORM;

trait EntityMetaData
{
    /**
     * @var \DateTime $created
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    /**
     * @var \DateTime $updated
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated;


    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    /**
     * Gets triggered only on insert
     * @ORM\PrePersist()
     * @return $this
     */
    public function setCreatedUpdated()
    {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();

        return $this;
    }

    /**
     * Gets triggered every time on update
     * @ORM\PreUpdate()
     * @return $this
     */
    public function setUpdated()
    {
        $this->updated = new \DateTime();

        return $this;
    }
}
