<?php

declare (strict_types = 1);

namespace Dumplie\Metadata\Infrastructure\Doctrine\Dbal;

use Doctrine\DBAL\Schema\Table;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field\BoolMapping;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field\DateTimeMapping;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field\DecimalMapping;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field\FloatMapping;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field\IntegerMapping;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field\MapMapping;
use Dumplie\Metadata\Schema\FieldDefinition;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field\AssociationMapping;
use Dumplie\Metadata\Infrastructure\Doctrine\Dbal\Field\TextMapping;

final class TypeRegistry
{
    /**
     * @var TypeMapping[]
     */
    private $mapping;

    /**
     * TypeRegistry constructor.
     *
     * @param array $typeMappings
     */
    public function __construct(array $typeMappings = [])
    {
        foreach ($typeMappings as $mapping) {
            $this->register($mapping);
        }
    }

    /**
     * @return TypeRegistry
     */
    public static function withDefaultTypes(): TypeRegistry
    {
        $registry = new static();
        $registry->register(new AssociationMapping());
        $registry->register(new TextMapping());
        $registry->register(new BoolMapping());
        $registry->register(new DateTimeMapping());
        $registry->register(new DecimalMapping());
        $registry->register(new FloatMapping());
        $registry->register(new IntegerMapping());
        $registry->register(new MapMapping());

        return $registry;
    }

    /**
     * @param TypeMapping $mapping
     */
    public function register(TypeMapping $mapping)
    {
        $this->mapping[] = $mapping;
    }

    /**
     * @param string          $schema
     * @param Table           $table
     * @param string          $name
     * @param FieldDefinition $definition
     *
     * @throws DoctrineStorageException
     */
    public function map(string $schema, Table $table, string $name, FieldDefinition $definition)
    {
        foreach ($this->mapping as $mapping) {
            if ($mapping->maps($definition->type())) {
                $mapping->map($schema, $table, $name, $definition);
                return;
            }
        }

        throw DoctrineStorageException::unableToMapType($definition->type());
    }
}
