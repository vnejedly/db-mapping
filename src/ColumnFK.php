<?php
namespace Hooloovoo\DatabaseMapping;
use Hooloovoo\DatabaseMapping\Descriptor\Table\TableInterface;

/**
 * Class ColumnFK
 */
class ColumnFK extends Column
{
    /**
     * @return string
     */
    public function getConstraintCatalog() : string
    {
        return $this->_description[TableInterface::KCU_CONSTRAINT_CATALOG];
    }

    /**
     * @return string
     */
    public function getConstraintSchema() : string
    {
        return $this->_description[TableInterface::KCU_CONSTRAINT_SCHEMA];
    }

    /**
     * @return string
     */
    public function getConstraintName() : string
    {
        return $this->_description[TableInterface::KCU_CONSTRAINT_NAME];
    }

    /**
     * @return int
     */
    public function getPositionInUniqueConstraint() : int
    {
        return (int) $this->_description[TableInterface::KCU_POSITION_IN_UNIQUE_CONSTRAINT];
    }

    /**
     * @return string
     */
    public function getReferencedTableSchema() : string
    {
        return $this->_description[TableInterface::KCU_REFERENCED_TABLE_SCHEMA];
    }

    /**
     * @return string
     */
    public function getReferencedTableName() : string
    {
        return $this->_description[TableInterface::KCU_REFERENCED_TABLE_NAME];
    }

    /**
     * @return string
     */
    public function getReferencedColumnName() : string
    {
        return $this->_description[TableInterface::KCU_REFERENCED_COLUMN_NAME];
    }
}