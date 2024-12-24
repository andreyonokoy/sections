<?php

namespace Bitrix\Maytoni\Sections\Configuration;

use Bitrix\Maytoni\Sections\DataMapper\DataMapperInterface;

class ConfigurationRepository
{
    protected array $conditionFieldText;
    public function __construct(protected DataMapperInterface $dataMapper)
    {
        $this->conditionFieldText = $this->setConditionFieldText();
    }
    private function setConditionFieldText(): array
    {
        $result = [];
        $fields = $this->dataMapper->getFields(false, ['UF_CONDITION']);
        $id = $fields['UF_CONDITION']['ID'];
        $ob = new \CUserFieldEnum();
        $rs = $ob->GetList([], [
            'USER_FIELD_ID' => $id,
        ]);
        while ($data = $rs->Fetch()) {
            $result[$data['ID']] = $data['VALUE'];
        }
        return $result;
    }

    private function getConditionFieldText($id)
    {
        return $this->conditionFieldText[$id];
    }


    public function getAll(): array
    {
        $result = [];
        $rows = $this->dataMapper->getAll();

        foreach ($rows as $row) {
            if (is_array($row["UF_VIEW"])) {
                foreach ($row["UF_VIEW"] as $view) {
                    $result[] = $this->renameRowData($row, $view);
                }
            } else {
                $result[] = $this->renameRowData($row, false);
            }

        }
        return $result;
    }

    public function getByBrand(string $brand): array
    {
        $result = [];
        if ($brand) {
            $rows = $this->dataMapper->getData([
                "filter" => ["UF_BRAND" => $brand],
            ]);

            foreach ($rows as $row) {
                if (is_array($row["UF_VIEW"])) {
                    foreach ($row["UF_VIEW"] as $view) {
                        $result[] = $this->renameRowData($row, $view);
                    }
                } else {
                    $result[] = $this->renameRowData($row, false);
                }

            }

        }
        return $result;
    }

    private function renameRowData(array $row, ?string $view): array
    {
        return [
            "brand"        => $row["UF_BRAND"],
            "view"         => $view ?? $view:: $row["UF_VIEW"],
            "collection"   => $row["UF_COLLECTION"],
            "type"         => $row["UF_TYPE"],
            "series"       => $row["UF_SERIES"],
            "sectionMatch" => $row["UF_MATCH"],
            "condition"    => $this->getConditionFieldText($row["UF_CONDITION"]),
        ];
    }


}