<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Device
 *
 * @ORM\Table(name="device")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DeviceRepository")
 */
class Device
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
     * @ORM\Column(name="warrantyExpirationDate", type="date")
     */
    private $warrantyExpirationDate;

    /**
     * @var string
     *
     * @ORM\Column(name="netPrice", type="decimal", precision=10, scale=0)
     */
    private $netPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="string", length=1024, nullable=true)
     */
    private $notes;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="devices")
     * @ORM\JoinColumn(name="device_id", referencedColumnName="id")
     */
    private $owner;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="Invoice", inversedBy="devices")
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
     * @return Device
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
     * @return Device
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
     * @return Device
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
     * Set warrantyExpirationDate
     *
     * @param \DateTime $warrantyExpirationDate
     *
     * @return Device
     */
    public function setWarrantyExpirationDate($warrantyExpirationDate)
    {
        $this->warrantyExpirationDate = $warrantyExpirationDate;

        return $this;
    }

    /**
     * Get warrantyExpirationDate
     *
     * @return \DateTime
     */
    public function getWarrantyExpirationDate()
    {
        return $this->warrantyExpirationDate;
    }

    /**
     * Set netPrice
     *
     * @param string $netPrice
     *
     * @return Device
     */
    public function setNetPrice($netPrice)
    {
        $this->netPrice = $netPrice;

        return $this;
    }

    /**
     * Get netPrice
     *
     * @return string
     */
    public function getNetPrice()
    {
        return $this->netPrice;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Device
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
     * @return Device
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
}

