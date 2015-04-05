<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 05/04/2015
 * Time: 14:19
 */

namespace WAL\IPFO;


class SearchResponse {

    private $applicationDate;
    private $applicationCountry;
    private $applicationNumber;

    private $publicationDate;
    private $publicationCountry;
    private $publicationNumber;

    private $grantDate;
    private $grantCountry;
    private $grantNumber;

    private $applicants;
    private $inventors;

    private $englishTitle;
    private $frenchTitle;
    private $germanTitle;

    private $source;

    /**
     * @return mixed
     */
    public function getSource() {
        return $this->source;
    }

    /**
     * @param mixed $source
     */
    public function setSource($source) {
        $this->source = $source;
    }

    /**
     * @return mixed
     */
    public function getApplicationDate() {
        return $this->applicationDate;
    }

    /**
     * @param mixed $applicationDate
     */
    public function setApplicationDate($applicationDate) {
        $this->applicationDate = $applicationDate;
    }

    /**
     * @return mixed
     */
    public function getApplicationCountry() {
        return $this->applicationCountry;
    }

    /**
     * @param mixed $applicationCountry
     */
    public function setApplicationCountry($applicationCountry) {
        $this->applicationCountry = $applicationCountry;
    }

    /**
     * @return mixed
     */
    public function getApplicationNumber() {
        return $this->applicationNumber;
    }

    /**
     * @param mixed $applicationNumber
     */
    public function setApplicationNumber($applicationNumber) {
        $this->applicationNumber = $applicationNumber;
    }

    /**
     * @return mixed
     */
    public function getPublicationDate() {
        return $this->publicationDate;
    }

    /**
     * @param mixed $publicationDate
     */
    public function setPublicationDate($publicationDate) {
        $this->publicationDate = $publicationDate;
    }

    /**
     * @return mixed
     */
    public function getPublicationCountry() {
        return $this->publicationCountry;
    }

    /**
     * @param mixed $publicationCountry
     */
    public function setPublicationCountry($publicationCountry) {
        $this->publicationCountry = $publicationCountry;
    }

    /**
     * @return mixed
     */
    public function getPublicationNumber() {
        return $this->publicationNumber;
    }

    /**
     * @param mixed $publicationNumber
     */
    public function setPublicationNumber($publicationNumber) {
        $this->publicationNumber = $publicationNumber;
    }

    /**
     * @return mixed
     */
    public function getGrantDate() {
        return $this->grantDate;
    }

    /**
     * @param mixed $grantDate
     */
    public function setGrantDate($grantDate) {
        $this->grantDate = $grantDate;
    }

    /**
     * @return mixed
     */
    public function getGrantCountry() {
        return $this->grantCountry;
    }

    /**
     * @param mixed $grantCountry
     */
    public function setGrantCountry($grantCountry) {
        $this->grantCountry = $grantCountry;
    }

    /**
     * @return mixed
     */
    public function getGrantNumber() {
        return $this->grantNumber;
    }

    /**
     * @param mixed $grantNumber
     */
    public function setGrantNumber($grantNumber) {
        $this->grantNumber = $grantNumber;
    }

    /**
     * @return mixed
     */
    public function getApplicants() {
        return $this->applicants;
    }

    /**
     * @param mixed $applicants
     */
    public function setApplicants($applicants) {
        $this->applicants = $applicants;
    }

    /**
     * @return mixed
     */
    public function getInventors() {
        return $this->inventors;
    }

    /**
     * @param mixed $inventors
     */
    public function setInventors($inventors) {
        $this->inventors = $inventors;
    }

    /**
     * @return mixed
     */
    public function getEnglishTitle() {
        return $this->englishTitle;
    }

    /**
     * @param mixed $englishTitle
     */
    public function setEnglishTitle($englishTitle) {
        $this->englishTitle = $englishTitle;
    }

    /**
     * @return mixed
     */
    public function getFrenchTitle() {
        return $this->frenchTitle;
    }

    /**
     * @param mixed $frenchTitle
     */
    public function setFrenchTitle($frenchTitle) {
        $this->frenchTitle = $frenchTitle;
    }

    /**
     * @return mixed
     */
    public function getGermanTitle() {
        return $this->germanTitle;
    }

    /**
     * @param mixed $germanTitle
     */
    public function setGermanTitle($germanTitle) {
        $this->germanTitle = $germanTitle;
    }

}