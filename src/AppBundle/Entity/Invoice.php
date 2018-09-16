<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Invoice
 *
 * @ORM\Table(name="invoice")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InvoiceRepository")
 */
class Invoice
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
     * @ORM\Column(name="invoice_number", type="string", length=255, unique=true)
     */
    private $invoiceNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="contractor_name", type="string", length=255)
     */
    private $contractorName;

    /**
     * @var string
     *
     * @ORM\Column(name="contractor_vatid", type="string", length=255)
     */
    private $contractorVatid;

    /**
     * @var string
     *
     * @ORM\Column(name="net_value", type="decimal", precision=10, scale=0)
     */
    private $netValue;

    /**
     * @var string
     *
     * @ORM\Column(name="gross_value", type="decimal", precision=10, scale=0)
     */
    private $grossValue;

    /**
     * @var string
     *
     * @ORM\Column(name="tax_value", type="decimal", precision=10, scale=0)
     */
    private $taxValue;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $scan;

    /**
     * @ORM\OneToMany(targetEntity="Device", mappedBy="invoice")
     */
    private $devices;


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
     * Set invoiceNumber
     *
     * @param string $invoiceNumber
     *
     * @return Invoice
     */
    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber
     *
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * Set contractorName
     *
     * @param string $contractorName
     *
     * @return Invoice
     */
    public function setContractorName($contractorName)
    {
        $this->contractorName = $contractorName;

        return $this;
    }

    /**
     * Get contractorName
     *
     * @return string
     */
    public function getContractorName()
    {
        return $this->contractorName;
    }

    /**
     * Set contractorVatid
     *
     * @param string $contractorVatid
     *
     * @return Invoice
     */
    public function setContractorVatid($contractorVatid)
    {
        $this->contractorVatid = $contractorVatid;

        return $this;
    }

    /**
     * Get contractorVatid
     *
     * @return string
     */
    public function getContractorVatid()
    {
        return $this->contractorVatid;
    }

    /**
     * Set netValue
     *
     * @param string $netValue
     *
     * @return Invoice
     */
    public function setNetValue($netValue)
    {
        $this->netValue = $netValue;

        return $this;
    }

    /**
     * Get netValue
     *
     * @return string
     */
    public function getNetValue()
    {
        return $this->netValue;
    }

    /**
     * Set grossValue
     *
     * @param string $grossValue
     *
     * @return Invoice
     */
    public function setGrossValue($grossValue)
    {
        $this->grossValue = $grossValue;

        return $this;
    }

    /**
     * Get grossValue
     *
     * @return string
     */
    public function getGrossValue()
    {
        return $this->grossValue;
    }

    /**
     * Set taxValue
     *
     * @param string $taxValue
     *
     * @return Invoice
     */
    public function setTaxValue($taxValue)
    {
        $this->taxValue = $taxValue;

        return $this;
    }

    /**
     * Get taxValue
     *
     * @return string
     */
    public function getTaxValue()
    {
        return $this->taxValue;
    }

    /**
     * @return mixed
     */
    public function getScan()
    {
        return $this->scan;
    }

    /**
     * @param mixed $scan
     */
    public function setScan($scan)
    {
        $this->scan = $scan;
    }

    /**
     * @return mixed
     */
    public function getDevices()
    {
        return $this->devices;
    }

    /**
     * @param mixed $devices
     */
    public function setDevices($devices)
    {
        $this->devices = $devices;
    }
}

