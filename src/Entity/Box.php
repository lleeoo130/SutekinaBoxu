<?php

namespace App\Entity;

use App\ArrayRemoveKeyTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoxRepository")
 */
class Box
{


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    /**
     * @ORM\Column(type="array")
     */
    private $status = [];

    /**
     * @ORM\Column(type="array")
     */
    private $products = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $budget;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $currentPlace;

    /**
     * Box constructor.
     * @param $name
     * @param $budget
     */
    public function __construct()
    {
        $this->status   = [
            'Ordered_from_catalogue'    => false,
            'Received_order'            => false,
            'Order_approved'            => false,
            'Ready_to_distribute'       => false,
        ];

        $this->currentPlace = 'waiting_to_order';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getStatus(): ?array
    {
        return $this->status;
    }

    public function setStatus(array $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getProducts(): ?array
    {
        return $this->products;
    }

    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function addProduct($product)
    {
        array_push($this->products, $product);


        if ( isset($product['price']))
        {
            $this->budget -= $product['price'];
        }
        return $this->setProducts(array_values($this->products));
    }

    public function removeProduct($productToRemove)
    {

        for ($i = 0; $i < count($this->products); $i++ )
        {

            if (isset($this->products[$i]['reference'])){
                if ( $this->products[$i]['reference'] == $productToRemove['reference'])
                {
                    unset($this->products[$i]);

                    if ( isset($productToRemove['price']))
                    {
                        $this->budget += $productToRemove['price'];
                    }

                }
            }
            return $this->setProducts(array_values($this->products));
        }
    }

    public function getBudget(): ?int
    {
        return $this->budget;
    }

    public function setBudget(int $budget): self
    {
        $this->budget = $budget;

        return $this;
    }

    public function changeStatus($status)
    {
        foreach ($this->status as $currentStatus => $value)
        {
            if ($status === $currentStatus)
            {
                if ($value === false)
                {
                    return $this->status[$status] = true;
                }
                else{
                    return $this->status[$status] = false;
                }
            }
        }
    }

}
