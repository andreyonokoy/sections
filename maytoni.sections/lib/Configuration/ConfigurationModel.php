<?php

namespace Bitrix\Maytoni\Sections\Configuration;

class ConfigurationModel
{
    protected string $collection;
    protected string $series;
    protected string $view; //тип товара
    protected string $type; //подтип товара
    protected string $condition;
    protected mixed $sectionMatch;


    public function __construct(
        string $collection,
        string $view,
        string $series,
        string $type,
        string $condition,
         $sectionMatch,
    )
    {
        $this->collection = $collection;
        $this->view = $view;
        $this->series = $series;
        $this->type = $type;
        $this->condition = $condition;
        $this->sectionMatch = $sectionMatch;
    }

    /**
     * @return string
     */
    public function getCollection(): string
    {
        return $this->collection;
    }

    /**
     * @return string
     */
    public function getCondition(): string
    {
        return $this->condition;
    }

    /**
     * @return string
     */
    public function getSectionMatch()
    {
        return $this->sectionMatch;
    }

    /**
     * @return string
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getView(): string
    {
        return $this->view;
    }

}