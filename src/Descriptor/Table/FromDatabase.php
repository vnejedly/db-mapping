<?php
namespace Hooloovoo\DatabaseMapping\Descriptor\Table;

use Hooloovoo\Database\Database;

/**
 * Class FromDatabase
 */
class FromDatabase implements TableInterface
{
    /** @var Database */
    protected $_database;

    /** @var string */
    protected $_schemaName;

    /** @var string */
    protected $_tableName;

    /**
     * Table constructor.
     * @param Database $database
     * @param string $schemaName
     * @param string $tableName
     */
    public function __construct(Database $database, string $schemaName, string $tableName)
    {
        $this->_database = $database;
        $this->_schemaName = $schemaName;
        $this->_tableName = $tableName;
    }

    /**
     * @return string
     */
    public function getSchemaName() : string
    {
        return $this->_schemaName;
    }

    /**
     * @return string
     */
    public function getTableName() : string
    {
        return $this->_tableName;
    }

    /**
     * @return array
     */
    public function getArray() : array
    {
        $query = $this->_database->createQuery("
            SELECT 
                c.table_catalog AS " . self::C_TABLE_CATALOG . ",
                c.table_schema AS " . self::C_TABLE_SCHEMA . ",
                c.table_name AS " . self::C_TABLE_NAME . ",
                c.column_name AS " . self::C_COLUMN_NAME . ",
                c.ordinal_position AS " . self::C_ORDINAL_POSITION . ",
                c.column_default AS " . self::C_COLUMN_DEFAULT . ",
                c.is_nullable AS " . self::C_IS_NULL_ABLE . ",
                c.data_type AS " . self::C_DATA_TYPE . ",
                c.character_maximum_length AS " . self::C_CHARACTER_MAXIMUM_LENGTH . ",
                c.character_octet_length AS " . self::C_CHARACTER_OCTET_LENGTH . ",
                c.numeric_precision AS " . self::C_NUMERIC_PRECISION . ",
                c.numeric_scale AS " . self::C_NUMERIC_SCALE . ",
                c.character_set_name AS " . self::C_CHARACTER_SET_NAME . ",
                c.collation_name AS " . self::C_COLLATION_NAME . ",
                c.column_type AS " . self::C_COLUMN_TYPE . ",
                c.column_key AS " . self::C_COLUMN_KEY . ",
                c.extra AS " . self::C_EXTRA . ",
                c.privileges AS " . self::C_PRIVILEGES . ",
                c.column_comment AS " . self::C_COLUMN_COMMENT . ",
                kcu.constraint_catalog AS " . self::KCU_CONSTRAINT_CATALOG . ",
                kcu.constraint_schema AS " . self::KCU_CONSTRAINT_SCHEMA . ",
                kcu.constraint_name AS " . self::KCU_CONSTRAINT_NAME . ",
                kcu.table_catalog AS " . self::KCU_TABLE_CATALOG . ",
                kcu.table_schema AS " . self::KCU_TABLE_SCHEMA . ",
                kcu.table_name AS " . self::KCU_TABLE_NAME . ",
                kcu.column_name AS " . self::KCU_COLUMN_NAME . ",
                kcu.ordinal_position AS " . self::KCU_ORDINAL_POSITION . ",
                kcu.position_in_unique_constraint AS " . self::KCU_POSITION_IN_UNIQUE_CONSTRAINT . ",
                kcu.referenced_table_schema AS " . self::KCU_REFERENCED_TABLE_SCHEMA . ",
                kcu.referenced_table_name AS " . self::KCU_REFERENCED_TABLE_NAME . ",
                kcu.referenced_column_name AS " . self::KCU_REFERENCED_COLUMN_NAME . "
                
            FROM information_schema.columns AS c
            
            LEFT JOIN (
                SELECT *
                FROM information_schema.key_column_usage
                WHERE referenced_table_name IS NOT NULL
                AND table_schema = :schemaName1 
                AND `table_name` = :tableName1
            ) AS kcu ON kcu.column_name = c.column_name
            
            WHERE c.table_schema = :schemaName2
            AND c.table_name = :tableName2
            
            ORDER BY c.ordinal_position ASC
        ");

        $query->addParam('schemaName1', $this->_schemaName, Database::PARAM_STR);
        $query->addParam('schemaName2', $this->_schemaName, Database::PARAM_STR);
        $query->addParam('tableName1', $this->_tableName, Database::PARAM_STR);
        $query->addParam('tableName2', $this->_tableName, Database::PARAM_STR);

        return $this->_database->getConnectionSlave()->execute($query)->fetchAll();
    }
}