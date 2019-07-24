<?php

namespace RvltDigital\SymfonyRevoltaBundle\Doctrine;

use Doctrine\ORM\Mapping\NamingStrategy as NamingStrategyInterface;
use Doctrine\ORM\Mapping\UnderscoreNamingStrategy;
use RvltDigital\StaticDiBundle\StaticDI;
use Symfony\Component\Inflector\Inflector;

class NamingStrategy implements NamingStrategyInterface
{
    /**
     * @var UnderscoreNamingStrategy|null
     */
    private $original = null;

    /**
     * Returns a table name for an entity class.
     *
     * @param string $className The fully-qualified class name.
     *
     * @return string A table name.
     */
    public function classToTableName($className)
    {
        $original = $this->getOriginal()->classToTableName($className);
        $pluralized = Inflector::pluralize($original);
        if (is_array($pluralized)) {
            $pluralized = $pluralized[0];
        }
        assert(is_string($pluralized));

        return $pluralized;
    }

    /**
     * Returns a column name for a property.
     *
     * @param string $propertyName A property name.
     * @param string|null $className The fully-qualified class name.
     *
     * @return string A column name.
     */
    public function propertyToColumnName($propertyName, $className = null)
    {
        return $this->getOriginal()->propertyToColumnName($propertyName, $className);
    }

    /**
     * Returns a column name for an embedded property.
     *
     * @param string $propertyName
     * @param string $embeddedColumnName
     * @param string $className
     * @param string $embeddedClassName
     *
     * @return string
     */
    public function embeddedFieldToColumnName($propertyName, $embeddedColumnName, $className = null, $embeddedClassName = null)
    {
        return $this->getOriginal()->embeddedFieldToColumnName($propertyName, $embeddedColumnName, $className, $embeddedClassName);
    }

    /**
     * Returns the default reference column name.
     *
     * @return string A column name.
     */
    public function referenceColumnName()
    {
        return $this->getOriginal()->referenceColumnName();
    }

    /**
     * Returns a join column name for a property.
     *
     * @param string $propertyName A property name.
     *
     * @return string A join column name.
     */
    public function joinColumnName($propertyName)
    {
        return $this->getOriginal()->joinColumnName($propertyName);
    }

    /**
     * Returns a join table name.
     *
     * @param string $sourceEntity The source entity.
     * @param string $targetEntity The target entity.
     * @param string|null $propertyName A property name.
     *
     * @return string A join table name.
     */
    public function joinTableName($sourceEntity, $targetEntity, $propertyName = null)
    {
        return sprintf(
            '%s_x_%s',
            $this->classToTableName($sourceEntity),
            $this->classToTableName($targetEntity)
        );
    }

    /**
     * Returns the foreign key column name for the given parameters.
     *
     * @param string $entityName An entity.
     * @param string|null $referencedColumnName A property.
     *
     * @return string A join column name.
     */
    public function joinKeyColumnName($entityName, $referencedColumnName = null)
    {
        return $this->getOriginal()->joinKeyColumnName($entityName, $referencedColumnName);
    }

    private function getOriginal(): UnderscoreNamingStrategy
    {
        if ($this->original === null) {
            $this->original = StaticDI::get('doctrine.orm.naming_strategy.underscore');
        }
        return $this->original;
    }
}
