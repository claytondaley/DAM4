<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 5/10/2015
 * Time: 9:49 AM
 */

namespace DAM4\Doctrine\Filter;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class DenyAll extends SQLFilter
{
    /**
     * @var array The list of tables that have been actively excluded from this protection
     */
    protected $excluded = [];

    /**
     * @param string $targetEntity The entity to be excluded from the filter
     */
    public function addExclusion($targetEntity)
    {
        $this->excluded[] = $targetEntity;
    }

    /**
     * @param string $targetEntity The entity to be (re)included in the filter
     */
    public function dropExclusion($targetEntity)
    {
        unset($this->excluded[$targetEntity]);
    }

    /**
     * Gets the SQL query part to add to a query.
     *
     * @param ClassMetadata $targetEntity
     * @param string $targetTableAlias
     *
     * @return string The constraint SQL if there is available, empty string otherwise.
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        # By returning 'WHERE true', we allow access to the table
        if (in_array($targetEntity->reflClass->getName(), $this->excluded)) {
            return '';
        }

        # In general, we need to relax our settings if we've reached this point so it's useful to leave an
        # easy-to-enable debugging function at this point
        die("blocking call for " . $targetEntity->reflClass->getName() . " at \n" .
            print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS),true));

        # By including `WHERE false`, we blocks access to all rows in the Doctrine tables
        return 'false';
    }
}