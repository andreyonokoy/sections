<?php

namespace Bitrix\Maytoni\Sections\Conditions;

enum MatchingTypes
{
    public const One = 'one'; //Не может быть больше двух секций с типом OnlyOne
    public const Many = 'many';  // Секций с Multi может быть сколько угодно
    public const None = 'none'; //Тип никак не участвующий в логике
}