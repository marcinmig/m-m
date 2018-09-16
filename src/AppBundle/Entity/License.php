<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * License
 *
 * @ORM\Table(name="license")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LicenseRepository")
 */
class License
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
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="serialNumber", type="string", length=255, unique=true)
     */
    private $serialNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="purchaseDate", type="date")
     */
    private $purchaseDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="supportExpirationDate", type="date")
     */
    private $supportExpirationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expirationDate", type="date", nullable=true)
     */
    private $expirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="string", length=1024, nullable=true)
     */
    private $notes;

    /**
     * @var mixed
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="licenses")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="licenses")
     * @ORM\JoinColumn(name="invoice_id", referencedColumnName="id")
     */
    private $invoice;


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
     * @return License
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
     * Set serialNumber
     *
     * @param string $serialNumber
     *
     * @return License
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * Get serialNumber
     *
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * Set purchaseDate
     *
     * @param \DateTime $purchaseDate
     *
     * @return License
     */
    public function setPurchaseDate($purchaseDate)
    {
        $this->purchaseDate = $purchaseDate;

        return $this;
    }

    /**
     * Get purchaseDate
     *
     * @return \DateTime
     */
    public function getPurchaseDate()
    {
        return $this->purchaseDate;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return License
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set owner
     *
     * @return License
     */
    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @return int
     */
    public function getInvoice()
    {
        return $this->invoice;
    }

    /**
     * @param int $invoice
     */
    public function setInvoice($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * @return \DateTime
     */
    public function getSupportExpirationDate()
    {
        return $this->supportExpirationDate;
    }

    /**
     * @param \DateTime $supportExpirationDate
     */
    public function setSupportExpirationDate($supportExpirationDate)
    {
        $this->supportExpirationDate = $supportExpirationDate;
    }

    /**
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }

    /**
     * @param \DateTime $expirationDate
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;
    }
}

