<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShoppinglistProduct
 *
 * @ORM\Table(name="shoppinglist_product", indexes={@ORM\Index(name="shoppinglist_product_product_id_fk", columns={"product_id"}), @ORM\Index(name="shoppinglist_product_shoppinglist_shoppinglist_id_fk", columns={"shoppinglist_id"})})
 * @ORM\Entity
 */
class ShoppinglistProduct
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="checked", type="boolean", nullable=true)
     */
    private $checked;

    /**
     * @var int|null
     *
     * @ORM\Column(name="qty", type="integer", nullable=true)
     */
    private $qty;

    /**
     * @var \Product
     *
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="product_id", referencedColumnName="product_id")
     * })
     */
    private $product;

    /**
     * @var \Shoppinglist
     *
     * @ORM\ManyToOne(targetEntity="Shoppinglist")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shoppinglist_id", referencedColumnName="shoppinglist_id")
     * })
     */
    private $shoppinglist;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(?bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }

    public function getQty(): ?int
    {
        return $this->qty;
    }

    public function setQty(?int $qty): self
    {
        $this->qty = $qty;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getShoppinglist(): ?Shoppinglist
    {
        return $this->shoppinglist;
    }

    public function setShoppinglist(?Shoppinglist $shoppinglist): self
    {
        $this->shoppinglist = $shoppinglist;

        return $this;
    }


}
