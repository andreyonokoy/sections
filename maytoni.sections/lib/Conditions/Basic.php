<?php

namespace Bitrix\Maytoni\Sections\Conditions;

use Bitrix\Maytoni\Sections\Configuration\ConfigurationInterface;
use Bitrix\Maytoni\Sections\Configuration\ConfigurationModel;
use Bitrix\Maytoni\Sections\MatchingResult\Basic as BasicMatchingResult;
use Bitrix\Maytoni\Sections\MatchingResult\MatchingResultInterface;
use Bitrix\Maytoni\Sections\ProductParametersModel;

abstract class Basic
{
    protected ConfigurationInterface $configuration;

    abstract protected function match(ProductParametersModel $parameters, ConfigurationModel $row): bool;

    abstract public function setMatchingType(ProductParametersModel $parameters): string;

    protected function setBasicMatch(ProductParametersModel $parameters, ConfigurationModel $row): MatchingResultInterface
    {
        $matching = new BasicMatchingResult();
        $section = $row->getSectionMatch();
        $condition = $row->getCondition();
        $priority = $this->getDefaultPriority();
        $matching->setMain($section);
        $matching->setAll($section);
        $matching->setType($this->setMatchingType($parameters));
        $matching->setCondition($condition);
        $matching->setPriority($priority);
        return $matching;
    }

    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
    }

    public function process(ProductParametersModel $parameters): MatchingResultInterface
    {
        $brand = $parameters->getBrand();
        $reflection = new \ReflectionClass($this);
        $condition = $reflection->getShortName();
        foreach ($this->configuration->getByBrandCondition($brand, $condition) as $row) {
            if ($this->match($parameters, $row)) {
                return $this->setBasicMatch($parameters, $row);
            }
        }
        return new BasicMatchingResult();
    }


}