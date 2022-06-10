<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Route
 *
 * @ORM\Table(name="route", indexes={@ORM\Index(name="route_store_store_id_fk", columns={"store_id"}), @ORM\Index(name="route_department_department_id_fk", columns={"department_id"})})
 * @ORM\Entity
 */
class ShopRoute
{
    /**
     * @var int
     *
     * @ORM\Column(name="route_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $routeId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     */
    private $sortOrder;

    /**
     * @var \Department
     *
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="department_id", referencedColumnName="department_id")
     * })
     */
    private $department;

    /**
     * @var \Store
     *
     * @ORM\ManyToOne(targetEntity="Store")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="store_id", referencedColumnName="store_id")
     * })
     */
    private $store;

    public function getRouteId(): ?int
    {
        return $this->routeId;
    }

    public function getSortOrder(): ?int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?int $sortOrder): self
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getDepartment(): ?Department
    {
        return $this->department;
    }

    public function setDepartment(?Department $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getStore(): ?Store
    {
        return $this->store;
    }

    public function setStore(?Store $store): self
    {
        $this->store = $store;

        return $this;
    }


}
