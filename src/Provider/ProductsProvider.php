<?php

namespace App\Provider;


use Symfony\Component\Yaml\Yaml;

class ProductsProvider
{
        private $products = [];
        private $source;

    /**
     * @return array
     */
    public function getProducts(): array
    {
        return $this->products;
    }

    /**
     * @param array $products
     */
    public function setProducts(array $products): void
    {
        $this->products = $products;
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source): void
    {
        $this->source = $source;
    }

    public function productArrayFromYaml(Yaml $yamlComponent, $yamlFile): array
    {
        return $this->products = $yamlComponent::parse($yamlFile);
    }
}