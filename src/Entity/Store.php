<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Store
 *
 * @ORM\Table(name="store")
 * @ORM\Entity
 */
class Store
{
    /**
     * @var int
     *
     * @ORM\Column(name="store_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $storeId;

    /**
     * @var string|null
     *
     * @ORM\Column(name="store_name", type="string", length=255, nullable=true)
     */
    private $storeName;

    /**
     * @var string|null
     *
     * @ORM\Column(name="store_img", type="string", length=255, nullable=true)
     */
    private $storeImg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $store_street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $store_nr;

    /**
     * @ORM\Column(type="string", length=35, nullable=true)
     */
    private $store_postalcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $store_city;

    public function getStoreId(): ?int
    {
        return $this->storeId;
    }

    public function getStoreName(): ?string
    {
        return $this->storeName;
    }

    public function setStoreName(?string $storeName): self
    {
        $this->storeName = $storeName;

        return $this;
    }

    public function getStoreImg(): ?string
    {
        return $this->storeImg;
    }

    public function setStoreImg(?string $storeImg): self
    {
        $this->storeImg = $storeImg;

        return $this;
    }

    public function getStoreStreet(): ?string
    {
        return $this->store_street;
    }

    public function setStoreStreet(?string $store_street): self
    {
        $this->store_street = $store_street;

        return $this;
    }

    public function getStoreNr(): ?string
    {
        return $this->store_nr;
    }

    public function setStoreNr(?string $store_nr): self
    {
        $this->store_nr = $store_nr;

        return $this;
    }

    public function getStorePostalcode(): ?string
    {
        return $this->store_postalcode;
    }

    public function setStorePostalcode(?string $store_postalcode): self
    {
        $this->store_postalcode = $store_postalcode;

        return $this;
    }

    public function getStoreCity(): ?string
    {
        return $this->store_city;
    }

    public function setStoreCity(?string $store_city): self
    {
        $this->store_city = $store_city;

        return $this;
    }


}
