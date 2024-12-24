<?php

namespace Bitrix\Maytoni\Sections\Configuration;

use Bitrix\Maytoni\Sections\Exception\SectionsException;

class ConfigurationBasic implements ConfigurationInterface
{
    protected array $configuration;

    public function __construct(ConfigurationRepository $repository)
    {
        $rows = $repository->getAll();
        foreach ($rows as $row) {
            $this->configuration[$row['brand']][$row['condition']][] = new ConfigurationModel(
                $row["collection"],
                $row['view'],
                $row["series"],
                $row["type"],
                $row['condition'],
                $row["sectionMatch"]);
        }
    }

    public function getByBrand(string $brand): array
    {
        if (!isset($this->configuration[$brand]) || count($this->configuration[$brand]) === 0) {
            throw new SectionsException('No configuration found for brand ' . $brand);
        }

        return $this->configuration[$brand];
    }

    public function getByBrandCondition(string $brand, string $condition):array
    {
        if (!isset($this->configuration[$brand][$condition]) || count($this->configuration[$brand][$condition]) === 0) {
            throw new SectionsException('No configuration found for brand '. $brand.' and condition' . $condition);
        }

        return $this->configuration[$brand][$condition];
    }

}