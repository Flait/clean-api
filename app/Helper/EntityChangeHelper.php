<?php

declare(strict_types=1);

namespace App\Helper;

use BackedEnum;
use ReflectionClass;
use ReflectionProperty;

final class EntityChangeHelper
{
    public static function diff(object $dto, object $entity): array
    {
        $changes = [];

        $dtoReflection = new ReflectionClass($dto);

        foreach ($dtoReflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $name = $property->getName();
            $dtoValue = $property->getValue($dto);

            // Skip null values – do not override entity with null
            if ($dtoValue === null) {
                continue;
            }

            $getter = 'get' . ucfirst($name);
            if (method_exists($entity, $getter)) {
                $entityValue = $entity->$getter();

                // Enum safe comparison
                if ($entityValue instanceof BackedEnum && $dtoValue instanceof BackedEnum) {
                    if ($entityValue->value !== $dtoValue->value) {
                        $changes[$name] = $dtoValue->value;
                    }
                } elseif ($entityValue !== $dtoValue) {
                    $changes[$name] = $dtoValue;
                }
            }
        }

        return $changes;
    }
}
