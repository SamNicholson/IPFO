<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 07/02/2016
 * Time: 23:49
 */

namespace SNicholson\IPFO\Parsers;


use Doctrine\Common\Collections\Expr\Value;
use SNicholson\IPFO\Helpers\IPFOXML;

class RecursiveFieldSearch
{
    /**
     * Recursively searches for the given key in an array
     * Returns false if it finds nothing, or the value of the key
     * if found
     *
     * @param                           $fieldName
     * @param IPFOXML|array             $searchArray
     *
     * @return IPFOXML
     *
     */
    public static function searchByName($fieldName, $searchArray)
    {
        /** @var IPFOXML $value */
        if ($searchArray InstanceOf IPFOXML) {
            return static::searchWithinIPFOXML($fieldName, $searchArray);
        } else if (is_array($searchArray)) {
            return static::searchWithinArray($fieldName, $searchArray);
        }
        return null;
    }

    public static function searchByNameArray(array $fieldNames, $searchArray)
    {
        foreach ($fieldNames as $fieldName) {
            if ($result = self::searchByName($fieldName, $searchArray)) {
                return $result;
            }
        }
        return null;
    }

    private static function searchWithinArray($fieldName, array $searchArray)
    {
        /** @var array $value */
        foreach ($searchArray as $name => $value) {
            if (is_string($value)) {
                return $value;
            }
            if ($name == $fieldName) {
                return $value;
            }
            if (is_array($value)) {
                $recursiveSearch = self::searchByName($fieldName, $value);
                if ($recursiveSearch !== null) {
                    if (!$recursiveSearch) {
                        return null;
                    }
                    return $recursiveSearch;
                }
            }
        }
        return null;
    }

    private static function searchWithinIPFOXML($fieldName, IPFOXML $searchArray)
    {
        /** @var IPFOXML $value */
        foreach ($searchArray as $value) {
            if (is_string($value)) {
                return $value;
            }
            if ($value->getName() == $fieldName) {
                return $value->getValue();
            }
            if ($value->count()) {
                $recursiveSearch = self::searchByName($fieldName, $value);
                if ($recursiveSearch !== null) {
                    if (!$recursiveSearch) {
                        return null;
                    }
                    return $recursiveSearch;
                }
            }
        }
        return null;
    }

    public static function getAllFieldsMatchingNameByArray($memberTypeNames, IPFOXML $membersArray)
    {
        foreach ($memberTypeNames as $field) {
            if ($response = $membersArray->xpath('//' . $field)) {
                return $response;
            }
        }
        return false;
    }
}