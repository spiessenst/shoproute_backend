<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shoppinglist
 *
 * @ORM\Table(name="shoppinglist")
 * @ORM\Entity
 */
class Shoppinglist
{
    /**
     * @var int
     *
     * @ORM\Column(name="shoppinglist_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $shoppinglistId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="shoppinglist_create_date", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $shoppinglistCreateDate = 'CURRENT_TIMESTAMP';

    /**
     * @var string|null
     *
     * @ORM\Column(name="shoppinglist_name", type="string", length=255, nullable=true)
     */
    private $shoppinglistName;

    public function getShoppinglistId(): ?int
    {
        return $this->shoppinglistId;
    }

    public function getShoppinglistCreateDate(): ?\DateTimeInterface
    {
        return $this->shoppinglistCreateDate;



    }

    //Datum als string
    public function getShoppinglistCreateDateString(): ?String
    {

        return date_format($this->shoppinglistCreateDate ,"Y/m/d H:i:s");

    }

    public function setShoppinglistCreateDate(\DateTimeInterface $shoppinglistCreateDate): self
    {
        $this->shoppinglistCreateDate = $shoppinglistCreateDate;

        return $this;
    }

    public function getShoppinglistName(): ?string
    {
        return $this->shoppinglistName;
    }

    public function setShoppinglistName(?string $shoppinglistName): self
    {
        $this->shoppinglistName = $shoppinglistName;

        return $this;
    }


}
