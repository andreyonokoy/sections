<?php

namespace Bitrix\Maytoni\Sections\DataMapper;

use Bitrix\Highloadblock\HighloadBlockTable;

class HighLoadDataMapper implements DataMapperInterface
{
    protected string $hlBlockId;
    protected string $hlBlockClass;

    protected string $hlPrefix = 'HLBLOCK_';

    public function __construct($name, protected string $languageId = LANGUAGE_ID)
    {
        ['ID' => $this->hlBlockId] = HighloadBlockTable::getList([
            'select' => ['ID', 'NAME', 'TABLE_NAME', 'FIELDS_COUNT'],
            'filter' => ['=NAME' => $name],
        ])->fetch();
        $this->hlBlockClass = HighloadBlockTable::getById($this->hlBlockId)->fetch();
    }

    public function getFields($list = false, $fields = [], $unfields = []): array
    {
        global $USER_FIELD_MANAGER;

        $userFields = $USER_FIELD_MANAGER->GetUserFields($this->hlPrefix . $this->hlBlockId, 0, $this->languageId);
        foreach ($userFields as $userField) {
            if (($list && $userField['SHOW_IN_LIST'] === 'Y') || !$list) {
                if (!empty($fields)) {
                    if (in_array($userField['FIELD_NAME'], $fields, true)) {
                        $userFields[$userField['FIELD_NAME']] = $userField;
                    }
                } else {
                    if (!empty($unfields)) {
                        if (!in_array($userField['FIELD_NAME'], $unfields, true)) {
                            $userFields[$userField['FIELD_NAME']] = $userField;
                        }
                    } else {
                        $userFields[$userField['FIELD_NAME']] = $userField;
                    }
                }
            }
        }
        return $userFields;
    }

    public function getEntity(): string
    {
        return $this->hlBlockClass;
    }

    public function getData($params = ["order" => ['ID'], "filter" => []]): array
    {
        global $USER_FIELD_MANAGER;

        $rows = [];
        $fieldsList = [];

        if (!isset($params['order'])) {
            $params['order'] = ['ID'];
        }

        $userFields = $USER_FIELD_MANAGER->GetUserFields($this->hlPrefix . $this->hlBlockId, 0, $this->languageId);
        foreach ($userFields as $userField) {
            $fieldsList[] = $userField['FIELD_NAME'];
        }

        if ($fieldsList) {
            $rows = $this->hlBlockClass::getList([
                'select' => $fieldsList,
                'order'  => $params['order'],
                'filter' => $params['filter'],
            ])->fetchAll();
        }


        return $rows;
    }


    public function getAll($params = ["order" => ['ID'], "filter" => []]): array
    {
        return $this->getData($params);
    }

    public function add($arFields): mixed
    {
        if (!empty($this->hlBlockClass)) {
            return $this->hlBlockClass::add($arFields);
        }
        return false;
    }

    public function update($id, $arFields): bool
    {
        if (!empty($this->hlBlockClass)) {
            $result = $this->hlBlockClass::update($id, $arFields);
            if ($result->getId()) {
                return true;
            }
        }
        return false;
    }

    public function delete($id): bool
    {
        if (!empty($this->hlBlockClass)) {
            return $this->hlBlockClass::delete($id);
        }
        return false;
    }
}