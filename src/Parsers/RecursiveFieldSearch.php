<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 07/02/2016
 * Time: 23:49
 */

namespace SNicholson\IPFO\Parsers;


class RecursiveFieldSearch
{
    /**
     * Recursively searches for the given key in an array
     * Returns false if it finds nothing, or the value of the key
     * if found
     *
     * @param       $fieldName
     * @param array $searchArray
     *
     * @return mixed
     */
    public static function searchByName($fieldName, array $searchArray)
    {
        foreach ($searchArray as $field => $value) {
            if ($field == $fieldName) {
                return $value;
            }
            if (is_array($value)) {
                $recursiveSearch = self::searchByName($fieldName, $value);
                if ($recursiveSearch !== false) {
                    return $recursiveSearch;
                }
            }
        }
        return false;
    }
}