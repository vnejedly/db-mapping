<?php
namespace Hooloovoo\DatabaseMapping\Descriptor;

/**
 * Interface DescriptorInterface
 */
interface DescriptorInterface
{
    /**
     * @return string
     */
    public function getSchemaName() : string ;

    /**
     * @return array
     */
    public function getArray() : array ;
}