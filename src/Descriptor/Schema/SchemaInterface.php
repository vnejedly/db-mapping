<?php
namespace Hooloovoo\DatabaseMapping\Descriptor\Schema;
use Hooloovoo\DatabaseMapping\Descriptor\DescriptorInterface;
use Hooloovoo\DatabaseMapping\Descriptor\Table\TableInterface as TableDescriptorInterface;

/**
 * Interface SchemaInterface
 */
interface SchemaInterface extends DescriptorInterface
{
    /**
     * @param string $tableName
     * @return TableDescriptorInterface
     */
    public function getTableDescriptor(string $tableName) : TableDescriptorInterface ;
}