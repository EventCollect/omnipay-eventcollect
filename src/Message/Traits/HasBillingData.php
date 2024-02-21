<?php

namespace Omnipay\EventCollect\Message\Traits;

trait HasBillingData
{
    /**
     * Get the first part of the card billing name.
     */
    public function getBillingFirstName(): ?string
    {
        return $this->getParameter('billingFirstName');
    }

    /**
     * Sets the first part of the card billing name.
     */
    public function setBillingFirstName($value): self
    {
        return $this->setParameter('billingFirstName', $value);
    }

    /**
     * Get the last part of the card billing name.
     */
    public function getBillingLastName(): ?string
    {
        return $this->getParameter('billingLastName');
    }

    /**
     * Sets the last part of the card billing name.
     */
    public function setBillingLastName($value): self
    {
        return $this->setParameter('billingLastName', $value);
    }

    /**
     * Get the billing company name.
     */
    public function getBillingCompany(): ?string
    {
        return $this->getParameter('billingCompany');
    }

    /**
     * Sets the billing company name.
     */
    public function setBillingCompany($value): self
    {
        return $this->setParameter('billingCompany', $value);
    }

    /**
     * Get the billing address, line 1.
     */
    public function getBillingAddress1(): ?string
    {
        return $this->getParameter('billingAddress1');
    }

    /**
     * Sets the billing address, line 1.
     */
    public function setBillingAddress1($value): self
    {
        return $this->setParameter('billingAddress1', $value);
    }

    /**
     * Get the billing address, line 2.
     */
    public function getBillingAddress2(): ?string
    {
        return $this->getParameter('billingAddress2');
    }

    /**
     * Sets the billing address, line 2.
     */
    public function setBillingAddress2($value): self
    {
        return $this->setParameter('billingAddress2', $value);
    }

    /**
     * Get the billing city.
     */
    public function getBillingCity(): ?string
    {
        return $this->getParameter('billingCity');
    }

    /**
     * Sets billing city.
     */
    public function setBillingCity($value): self
    {
        return $this->setParameter('billingCity', $value);
    }

    /**
     * Get the billing postcode.
     */
    public function getBillingPostcode(): ?string
    {
        return $this->getParameter('billingPostcode');
    }

    /**
     * Sets the billing postcode.
     */
    public function setBillingPostcode($value): self
    {
        return $this->setParameter('billingPostcode', $value);
    }

    /**
     * Get the billing state.
     */
    public function getBillingState(): ?string
    {
        return $this->getParameter('billingState');
    }

    /**
     * Sets the billing state.
     */
    public function setBillingState($value): self
    {
        return $this->setParameter('billingState', $value);
    }

    /**
     * Get the billing country name.
     */
    public function getBillingCountry(): ?string
    {
        return $this->getParameter('billingCountry');
    }

    /**
     * Sets the billing country name.
     */
    public function setBillingCountry($value): self
    {
        return $this->setParameter('billingCountry', $value);
    }

    /**
     * Get the billing phone number.
     */
    public function getBillingPhone(): ?string
    {
        return $this->getParameter('billingPhone');
    }

    /**
     * Sets the billing phone number.
     */
    public function setBillingPhone($value): self
    {
        return $this->setParameter('billingPhone', $value);
    }
}
