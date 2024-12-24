<?php

namespace Bitrix\Maytoni\Sections;

class ProductParametersModel
{
    protected string $brand;
    protected string $collection;
    protected string $series;
    protected string $view; //тип товара
    protected array $additional;
    protected string $type;


    public function __construct(
        string $brand,
        string $collection,
        string $view,
        string $series,
        string $type,
        array  $additional = [])
    {
        $this->brand = $brand;
        $this->collection = $collection;
        $this->series = $series;
        $this->view = $view; //тип товара
        $this->additional = $additional;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getSeries():string
    {
        return $this->series;
    }

    public function getCollection():string
    {
        return $this->collection;
    }

    public function updateCollection(string $collection):void
    {
        $this->collection=$collection;
    }

    public function getView()
    {
        return $this->view;
    }

    public function getAdditional(string $name):mixed
    {
        if(isset($this->additional[$name]))
        {
            return $this->additional[$name];
        }
        return false;
    }

    public function getType():string
    {
        return $this->type;
    }


}