<?php

namespace OneShot\Builder\Enum\Templates;

enum StubsFilesNameEnum: string
{
    case API_RESOURCES = 'create.api-resources.stub';
    case CRUD_TRAIT     = 'create.crud-trait.stub';
    case SERVICE        = 'create.service.stub';
    case TRAITS         = 'create.trait.stub';
    case ENUM           = 'create.enum.stub';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}