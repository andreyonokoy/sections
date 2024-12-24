<?php
namespace Bitrix\Maytoni\Sections\DataMapper;
interface DataMapperInterface
{
    public function getData(array $params = ["order" => ['ID'], "filter" => []]): array;

    public function getAll(): array;

    public function getFields():array;



}