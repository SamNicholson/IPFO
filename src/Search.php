<?php

namespace SNicholson\IPFO;

use InvalidArgumentException;
use SNicholson\IPFO\Containers\RequestsContainer;
use SNicholson\IPFO\Searches\OfficeSearch;
use SNicholson\IPFO\Searches\PatentSearch;
use SNicholson\IPFO\Searches\TrademarkSearch;
use SNicholson\IPFO\Interfaces\SearchInterface;
use WorkAnyWare\IPFO\IPRights\SearchType;
use WorkAnyWare\IPFO\IPRights\Number;

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
        return new Search(SearchType::tradeMark());
    }

    /**
     * Instantiates a new search for a patent
     * @return Search
     *
     */
    public static function patent()
    {
        return new Search(SearchType::patent());
    }

    public static function EPO()
    {
        return new Search(SearchType::EPO());
    }

    public static function WIPO()
    {
        return new Search(SearchType::WIPO());
    }

    public static function USPTO()
    {
        return new Search(SearchType::USPTO());
    }

    /**
     * Sets the IP type for the Search, e.g. Patent or Trademark
     *
     * @param SearchType $IPType
     */
    private function __construct(SearchType $IPType)
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
     * @return SearchResults
     */
    public function run(SearchInterface $searchObject = null)
    {
        $searchClass = $this->getSearch($searchObject);
        $result = $searchClass->numberSearch($this->getNumber());
        $results = new SearchResults();
        if (!is_bool($result)) {
            $results->setSuccess(true);
            $results->addResponse($result);
        } else {
            $results->setSuccess(false);
        }
        return $results;
    }

    /**
     *
     * @param $search
     *
     * @return PatentSearch|TrademarkSearch
     */
    private function getSearch($search)
    {
        $requestsContainer = new RequestsContainer();
        if ($search === null) {
            switch ($this->getIPType()) {
                case SearchType::TRADEMARK:
                    return new TrademarkSearch($requestsContainer);
                    break;
                case SearchType::PATENT:
                    return new PatentSearch($requestsContainer);
                    break;
                case SearchType::EPO:
                case SearchType::WIPO:
                case SearchType::USPTO:
                    return new OfficeSearch($requestsContainer, $this->getIPType());
                    break;
                default:
                    throw new InvalidArgumentException("Invalid Search Interface Provided");
            }
        }
        return $search;
    }

} 