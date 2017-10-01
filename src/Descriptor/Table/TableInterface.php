<?php
namespace Hooloovoo\DatabaseMapping\Descriptor\Table;

use Hooloovoo\DatabaseMapping\Descriptor\DescriptorInterface;

/**
 * Interface TableInterface
 */
interface TableInterface extends DescriptorInterface
{
    /** 'columns' table columns */
    const C_TABLE_CATALOG = 'c_table_catalog';
    const C_TABLE_SCHEMA = 'c_table_schema';
    const C_TABLE_NAME = 'c_table_name';
    const C_COLUMN_NAME = 'c_column_name';
    const C_ORDINAL_POSITION = 'c_ordinal_position';
    const C_COLUMN_DEFAULT = 'c_column_default';
    const C_IS_NULL_ABLE = 'c_is_null_able';
    const C_DATA_TYPE = 'c_data_type';
    const C_CHARACTER_MAXIMUM_LENGTH = 'c_character_maximum_length';
    const C_CHARACTER_OCTET_LENGTH = 'c_character_octet_length';
    const C_NUMERIC_PRECISION = 'c_numeric_precision';
    const C_NUMERIC_SCALE = 'c_numeric_scale';
    const C_CHARACTER_SET_NAME = 'c_character_set_name';
    const C_COLLATION_NAME = 'c_collation_name';
    const C_COLUMN_TYPE = 'c_column_type';
    const C_COLUMN_KEY = 'c_column_key';
    const C_EXTRA = 'c_extra';
    const C_PRIVILEGES = 'c_privileges';
    const C_COLUMN_COMMENT = 'c_column_comment';

    /** 'key_column_usage' table columns */
    const KCU_CONSTRAINT_CATALOG = 'kcu_constraint_catalog';
    const KCU_CONSTRAINT_SCHEMA = 'kcu_constraint_schema';
    const KCU_CONSTRAINT_NAME = 'kcu_constraint_name';
    const KCU_TABLE_CATALOG = 'kcu_table_catalog';
    const KCU_TABLE_SCHEMA = 'kcu_table_schema';
    const KCU_TABLE_NAME = 'kcu_table_name';
    const KCU_COLUMN_NAME = 'kcu_column_name';
    const KCU_ORDINAL_POSITION = 'kcu_ordinal_position';
    const KCU_POSITION_IN_UNIQUE_CONSTRAINT = 'kcu_position_in_unique_constraint';
    const KCU_REFERENCED_TABLE_SCHEMA = 'kcu_referenced_table_schema';
    const KCU_REFERENCED_TABLE_NAME = 'kcu_referenced_table_name';
    const KCU_REFERENCED_COLUMN_NAME = 'kcu_referenced_column_name';

    /**
     * @return string
     */
    public function getTableName() : string ;
}