<?php

namespace OneShot\Builder\Enum\Templates;

enum StubsFilesEnum: string
{
    case API_CONTROLLER = 'create.api-resources.stub';
    case SERVICE        = 'create.service.stub';
    case TRAITS         = 'create.trait.stub';
    case ENUM           = 'create.enum.stub';


    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}