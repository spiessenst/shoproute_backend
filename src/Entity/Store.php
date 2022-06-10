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


}
