<?php
namespace Hooloovoo\DatabaseMapping;

use Hooloovoo\DatabaseMapping\Descriptor\Schema\SchemaInterface as SchemaDescriptor;
use Hooloovoo\DatabaseMapping\Exception\LogicException;

/**
 * Class Schema
 */
class Schema
{
    /** @var SchemaDescriptor */
    protected $_schemaDescriptor;

    /** @var Table[] */
    protected $_tables = [];

    /**
     * Schema constructor.
     * @param SchemaDescriptor $schemaDescriptor
     */
    public function __construct(SchemaDescriptor $schemaDescriptor)
    {
        $this->_schemaDescriptor = $schemaDescriptor;
        $this->_tables = $this->_initTables($schemaDescriptor);
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->_schemaDescriptor->getSchemaName();
    }

    /**
     * @return Table[]
     */
    public function getTables() : array
    {
        return $this->_tables;
    }

    /**
     * @param string $tableName
     * @return Table
     */
    public function getTable(string $tableName) : Table
    {
        if (!array_key_exists($tableName, $this->_tables)) {
            throw new LogicException("Table $tableName doesn't exist");
        }

        return $this->_tables[$tableName];
    }

    /**
     * @param string $parentTable
     * @param string $childTable
     * @param string $mapTableName
     * @return Table
     */
    public function getMapTable(string $parentTable, string $childTable, string $mapTableName) : Table
    {
        $mapTable = $this->getTable($mapTableName);

        if (!$mapTable->hasReference($parentTable) || !$mapTable->hasReference($childTable)) {
            throw new LogicException("Table {$mapTableName} does not map tables $parentTable, $childTable");
        }

        return $mapTable;
    }

    /**
     * @param string $parentTable
     * @param string $childTable
     * @return Table
     */
    public function findMapTable(string $parentTable, string $childTable) : Table
    {
        foreach ($this->getTables() as $table) {
            if (
                $table->hasReference($parentTable) &&
                $table->hasReference($childTable) &&
                !$table->hasSimplePrimaryKey()
            ) {
                return $table;
            }
        }

        throw new LogicException("Map table not found for tables $parentTable, $childTable");
    }

    /**
     * @param SchemaDescriptor $schemaDescriptor
     * @return Table[]
     */
    protected function _initTables(SchemaDescriptor $schemaDescriptor) : array
    {
        $tables = [];
        foreach ($schemaDescriptor->getArray() as $tableName) {
            $tables[$tableName] = new Table($schemaDescriptor->getTableDescriptor($tableName));
        }

        return $tables;
    }
}