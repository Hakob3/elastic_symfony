<?php

namespace App\Service;

use Symfony\Bundle\MakerBundle\Str;

class ClassHelper
{
    /**
     * @param string|object $entity
     * @return string
     */
    public static function getEntityIndexName(string|object $entity): string
    {
        if (!is_string($entity)){
            $entity = get_class($entity);
        }

        return Str::asSnakeCase(Str::getShortClassName($entity));
    }
}