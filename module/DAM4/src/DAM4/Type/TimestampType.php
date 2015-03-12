<?php
/**
 * User: Clayton Daley
 * Date: 1/4/2015
 * Time: 2:08 PM
 */

namespace DAM4\Type;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Type that maps an SQL TIME to a PHP DateTime object.
*/
class TimestampType extends Type
{
    const MYTYPE = 'timestamp'; // modify to match your type name

    /**
     * {@inheritdoc}
     */
    public function getSqlDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return "TIMESTAMP";
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $val = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
        if (!$val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), 'Y-m-d H:i:s');
        }
        return $val;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        // If the value of the field is NULL the method convertToDatabaseValue() is not called.
        // http://doctrine-orm.readthedocs.org/en/latest/cookbook/custom-mapping-types.html
        return $value->format('Y-m-d H:i:s');
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::MYTYPE; // modify to match your constant name
    }
}