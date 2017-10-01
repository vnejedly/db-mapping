<?php
namespace Hooloovoo\DatabaseMapping;
use Hooloovoo\DatabaseMapping\Descriptor\Table\TableInterface;

/**
 * Class Column
 */
class Column
{
    /** @var mixed[] */
    protected $_description;

    /**
     * Column constructor.
     * @param mixed[] $description
     */
    public function __construct(array $description)
    {
        $this->_description = $description;
    }

    /**
     * @return string
     */
    public function getTableCatalog() : string
    {
        return $this->_description[TableInterface::C_TABLE_CATALOG];
    }

    /**
     * @return string
     */
    public function getTableSchema() : string
    {
        return $this->_description[TableInterface::C_TABLE_SCHEMA];
    }

    /**
     * @return string
     */
    public function getTableName() : string
    {
        return $this->_description[TableInterface::C_TABLE_NAME];
    }

    /**
     * @return string
     */
    public function getColumnName() : string
    {
        return $this->_description[TableInterface::C_COLUMN_NAME];
    }

    /**
     * @return int
     */
    public function getOrdinalPosition() : int
    {
        return (int) $this->_description[TableInterface::C_ORDINAL_POSITION];
    }

    /**
     * @return mixed
     */
    public function getColumnDefault()
    {
        return $this->_description[TableInterface::C_COLUMN_DEFAULT];
    }

    /**
     * @return bool
     */
    public function getIsNullAble() : bool
    {
        $map = [
            'YES' => true,
            'NO' => false
        ];

        return $map[$this->_description[TableInterface::C_IS_NULL_ABLE]];
    }

    /**
     * @return bool
     */
    public function getIsAutoIncrement() : bool
    {
        if ($this->getExtra() == 'auto_increment') {
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getDataType() : string
    {
        return $this->_description[TableInterface::C_DATA_TYPE];
    }

    /**
     * @return int
     */
    public function getCharacterMaximumLength() : int
    {
        return (int) $this->_description[TableInterface::C_CHARACTER_MAXIMUM_LENGTH];
    }

    /**
     * @return int
     */
    public function getCharacterOctetLength() : int
    {
        return (int) $this->_description[TableInterface::C_CHARACTER_OCTET_LENGTH];
    }

    /**
     * @return int
     */
    public function getNumericPrecision() : int
    {
        return (int) $this->_description[TableInterface::C_NUMERIC_PRECISION];
    }

    /**
     * @return int
     */
    public function getNumericScale() : int
    {
        return (int) $this->_description[TableInterface::C_NUMERIC_SCALE];
    }

    /**
     * @return string
     */
    public function getCharacterSetName() : string
    {
        return $this->_description[TableInterface::C_CHARACTER_SET_NAME];
    }

    /**
     * @return string
     */
    public function getCollationName() : string
    {
        return $this->_description[TableInterface::C_COLUMN_NAME];
    }

    /**
     * @return string
     */
    public function getColumnType() : string
    {
        return $this->_description[TableInterface::C_COLUMN_TYPE];
    }

    /**
     * @return string
     */
    public function getColumnKey() : string
    {
        return $this->_description[TableInterface::C_COLUMN_KEY];
    }

    /**
     * @return bool
     */
    public function getIsPrimaryKey() : bool
    {
        return strpos($this->getColumnKey(), 'PRI') !== false;
    }

    /**
     * @return bool
     */
    public function getIsUnique() : bool
    {
        return strpos($this->getColumnKey(), 'UNI') !== false;
    }

    /**
     * @return string
     */
    public function getExtra() : string
    {
        return $this->_description[TableInterface::C_EXTRA];
    }

    /**
     * @return string[]
     */
    public function getPrivileges() : array
    {
        return explode(',', $this->_description[TableInterface::C_PRIVILEGES]);
    }

    /**
     * @return string
     */
    public function getColumnComment() : string
    {
        return $this->_description[TableInterface::C_COLUMN_COMMENT];
    }

    /**
     * @return array
     */
    public function getDescriptionArray() : array
    {
        return $this->_description;
    }

    /**
     * @return string
     */
    public function getEntityFieldName() : string
    {
        return $this->_camelCase($this->getColumnName());
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