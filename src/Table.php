<?php
namespace Hooloovoo\DatabaseMapping;

use Hooloovoo\DatabaseMapping\Descriptor\Table\TableInterface as TableDescriptor;
use Hooloovoo\DatabaseMapping\Descriptor\Table\TableInterface;
use Hooloovoo\DatabaseMapping\Exception\LogicException;
use Hooloovoo\DatabaseMapping\Exception\MultiplePrimaryKeyException;
use Hooloovoo\DatabaseMapping\Exception\NoPrimaryKeyException;
use Hooloovoo\ORM\Exception\NonExistingColumnException;
use Hooloovoo\ORM\Exception\NonExistingFieldException;

/**
 * Class Table
 */
class Table
{
    /** @var TableDescriptor */
    protected $_tableDescriptor;

    /** @var Column[] */
    protected $_primaryKey = [];

    /** @var Column[] */
    protected $_columns = [];

    /** @var Column[] */
    protected $_nonKeyColumns = [];

    /** @var Column[] */
    protected $_nonFKColumns = [];

    /** @var ColumnFK[] */
    protected $_fkColumns = [];

    /** @var string */
    protected $_entityName;

    /** @var string[] */
    protected $_entityTableMapping;

    /** @var string[] */
    protected $_tableEntityMapping;

    /**
     * Table constructor.
     * @param TableDescriptor $tableDescriptor
     */
    public function __construct(TableDescriptor $tableDescriptor)
    {
        $this->_tableDescriptor = $tableDescriptor;
        $this->_initColumns($tableDescriptor);
    }

    /**
     * @return string
     */
    public function getName() : string
    {
        return $this->_tableDescriptor->getTableName();
    }

    /**
     * @return Column[]
     */
    public function getColumns() : array
    {
        return $this->_columns;
    }

    /**
     * @param string $name
     * @return Column
     */
    public function getColumn(string $name) : Column
    {
        foreach ($this->getColumns() as $column) {
            if ($column->getColumnName() == $name) {
                return $column;
            }
        }

        throw new LogicException("Column $name not found");
    }

    /**
     * @return Column[]
     */
    public function getNonKeyColumns() : array
    {
        return $this->_nonKeyColumns;
    }

    /**
     * @return Column[]
     */
    public function getNonFKColumns() : array
    {
        return $this->_nonFKColumns;
    }

    /**
     * @return string[]
     */
    public function getColumnNames() : array
    {
        $names = [];
        foreach ($this->getColumns() as $column) {
            $names[] = $column->getColumnName();
        }

        return $names;
    }

    /**
     * @return ColumnFK[]
     */
    public function getFkColumns() : array
    {
        return $this->_fkColumns;
    }

    /**
     * @param string $referencedTable
     * @return bool
     */
    public function hasReference(string $referencedTable) : bool
    {
        foreach ($this->getFkColumns() as $fkColumn) {
            if ($fkColumn->getReferencedTableName() == $referencedTable) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $referencedTable
     * @return ColumnFK
     */
    public function getReferencingColumn(string $referencedTable) : ColumnFK
    {
        foreach ($this->getFkColumns() as $fkColumn) {
            if ($fkColumn->getReferencedTableName() == $referencedTable) {
                return $fkColumn;
            }
        }

        throw new LogicException("Table {$this->getName()} doesn't reference table $referencedTable");
    }

    /**
     * @return bool
     */
    public function hasPrimaryKey() : bool
    {
        return (count($this->_primaryKey) > 0);
    }

    /**
     * @return bool
     */
    public function hasMultiplePrimaryKey() : bool
    {
        return (count($this->_primaryKey) > 1);
    }

    /**
     * @return Column[]
     */
    public function getPrimaryKey() : array
    {
        return $this->_primaryKey;
    }

    /**
     * @return Column
     * @throws NoPrimaryKeyException
     * @throws MultiplePrimaryKeyException
     */
    public function getSimplePrimaryKey() : Column
    {
        if (!$this->hasPrimaryKey()) {
            throw new NoPrimaryKeyException("Table '{$this->getName()}' has no primary key");
        }

        if ($this->hasMultiplePrimaryKey()) {
            throw new MultiplePrimaryKeyException("Table '{$this->getName()}' has multiple key");
        }

        return $this->_primaryKey[0];
    }

    /**
     * @return TableInterface
     */
    public function getDescriptor() : TableDescriptor
    {
        return $this->_tableDescriptor;
    }

    /**
     * @param TableDescriptor $tableDescriptor
     */
    protected function _initColumns(TableDescriptor $tableDescriptor)
    {
        foreach ($tableDescriptor->getArray() as $columnDescription) {
            $isFK = !is_null($columnDescription[TableInterface::KCU_REFERENCED_TABLE_NAME]);
            $isPK = $columnDescription[TableInterface::C_COLUMN_KEY] == 'PRI';

            if ($isFK) {
                $column = new ColumnFK($columnDescription);
                $this->_fkColumns[] = $column;
            } else {
                $column = new Column($columnDescription);
                $this->_nonKeyColumns[] = $column;
            }

            if ($isPK) {
                $this->_primaryKey[] = $column;
            }

            if (!$isFK && !$isPK) {
                $this->_nonKeyColumns[] = $column;
            }

            $this->_columns[] = $column;
        }
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        if (is_null($this->_entityName)) {
            $this->_entityName = $this->_capitalize($this->getName());
        }

        return $this->_entityName;
    }

    /**
     * @return string[]
     */
    public function getEntityFieldNames() : array
    {
        $names = [];
        foreach ($this->getColumns() as $column) {
            $names[] = $column->getEntityFieldName();
        }

        return $names;
    }

    /**
     * @return string[]
     */
    public function getEntityTableMapping() : array
    {
        if (is_null($this->_entityTableMapping)) {
            $this->_entityTableMapping = [];
            foreach ($this->getColumns() as $column) {
                $this->_entityTableMapping[$column->getColumnName()] = $column->getEntityFieldName();
            }
        }

        return $this->_entityTableMapping;
    }

    /**
     * @return string[]
     */
    public function getTableEntityMapping() : array
    {
        if (is_null($this->_tableEntityMapping)) {
            $this->_tableEntityMapping = [];
            foreach ($this->getColumns() as $column) {
                $this->_tableEntityMapping[$column->getEntityFieldName()] = $column->getColumnName();
            }
        }

        return $this->_tableEntityMapping;
    }

    /**
     * @param string $columnName
     * @return string
     * @throws NonExistingColumnException
     */
    public function getFieldForColumn(string $columnName) : string
    {
        $mapping = $this->getEntityTableMapping();
        if (!array_key_exists($columnName, $mapping)) {
            throw new NonExistingColumnException("Column $columnName does not exist for table {$this->getName()}");
        }

        return $mapping[$columnName];
    }

    /**
     * @param string $fieldName
     * @return string
     * @throws NonExistingFieldException
     */
    public function getColumnForField(string $fieldName) : string
    {
        $mapping = $this->getTableEntityMapping();
        if (!array_key_exists($fieldName, $mapping)) {
            throw new NonExistingFieldException("Field $fieldName does not exist for entity {$this->getEntityName()}");
        }

        return $mapping[$fieldName];
    }

    /**
     * @param string $name
     * @return string
     */
    protected function _camelCase(string $name) : string
    {
        return lcfirst($this->_capitalize($name));
    }

    /**
     * @param string $name
     * @return string
     */
    protected function _capitalize(string $name) : string
    {
        return str_replace('_', '', ucwords($name, '_'));
    }
}