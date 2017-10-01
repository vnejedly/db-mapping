<?php
namespace Hooloovoo\DatabaseMapping\Descriptor\Schema;

use Hooloovoo\Database\Database;
use Hooloovoo\DatabaseMapping\Descriptor\Table\FromDatabase as TableDescriptor;
use Hooloovoo\DatabaseMapping\Descriptor\Table\TableInterface as TableDescriptorInterface;

/**
 * Class FromDatabase
 */
class FromDatabase implements SchemaInterface
{
    /** @var Database */
    protected $_database;

    /** @var string */
    protected $_schemaName;

    /**
     * Table constructor.
     * @param Database $database
     * @param string $schemaName
     */
    public function __construct(Database $database, string $schemaName)
    {
        $this->_database = $database;
        $this->_schemaName = $schemaName;
    }

    /**
     * @return string
     */
    public function getSchemaName() : string
    {
        return $this->_schemaName;
    }

    /**
     * @return array
     */
    public function getArray() : array
    {
        $query = $this->_database->createQuery("
            SHOW TABLES
        ");

        $resultSet = $this->_database->getConnectionSlave()->execute($query)->fetchAll(false);
        array_walk($resultSet, function (&$value) {
            $value = (string) $value[0];
        });

        return $resultSet;
    }

    /**
     * @param string $tableName
     * @return TableDescriptorInterface
     */
    public function getTableDescriptor(string $tableName) : TableDescriptorInterface
    {
        return new TableDescriptor($this->_database, $this->_schemaName, $tableName);
    }
}