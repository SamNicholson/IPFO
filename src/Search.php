<?php

namespace SNicholson\IPFO;

use InvalidArgumentException;
use SNicholson\IPFO\Searches\PatentSearch;
use SNicholson\IPFO\Searches\TrademarkSearch;
use SNicholson\IPFO\Interfaces\SearchInterface;
use SNicholson\IPFO\ValueObjects\IPType;
use SNicholson\IPFO\ValueObjects\Number;

/**
 * Class Search
 * @package SNicholson\IPFO
 */
class Search
{

    /**
     * @var
     */
    private $IPType;
    /**
     * @var
     */
    private $number;

    /**
     * Instantiates a new search for a trademark
     * @return Search
     *
     */
    public static function tradeMark()
    {
        return new Search(IPType::tradeMark());
    }

    /**
     * Instantiates a new search for a patent
     * @return Search
     *
     */
    public static function patent()
    {
        return new Search(IPType::patent());
    }

    /**
     * Sets the IP type for the Search, e.g. Patent or Trademark
     *
     * @param IPType $IPType
     */
    private function __construct(IPType $IPType)
    {
        $this->IPType = $IPType;
    }

    /**
     * @param $number
     *
     * @return Search
     */
    public function byApplicationNumber($number)
    {
        $this->number = Number::application($number);
        return $this;
    }

    /**
     * @param $number
     *
     * @return Search
     */
    public function byPublicationNumber($number)
    {
        $this->number = Number::publication($number);
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @return mixed
     */
    public function getIPType()
    {
        return $this->IPType;
    }

    /**
     * Search the Official Offices for the information regarding the number you entered, will allow injection of a
     * custom search object, for testing and customisation purposes
     *
     * @param SearchInterface $searchObject
     *
     * @return ResultCollection
     */
    public function search(SearchInterface $searchObject = null)
    {
        $searchClass = $this->getSearch($searchObject);
        $searchClass->numberSearch($this->getNumber());
        return $searchClass->getResultCollection();
    }

    /**
     *
     * @param $search
     *
     * @return PatentSearch|TrademarkSearch
     */
    private function getSearch($search)
    {
        if ($search === null) {
            switch ($this->getIPType()) {
                case IPType::TRADEMARK:
                    return new TrademarkSearch();
                    break;
                case IPType::PATENT:
                    return new PatentSearch();
                    break;
                default:
                    throw new InvalidArgumentException("Invalid Search Interface Provided");
            }
        }
        return $search;
    }

} 