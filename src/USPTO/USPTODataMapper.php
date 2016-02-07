<?php

namespace SNicholson\IPFO\USPTO;

use DateTime;
use SNicholson\IPFO\Interfaces\DataMapperInterface;
use SNicholson\IPFO\Abstracts\DataMapper;
use SNicholson\IPFO\IPRight;
use SNicholson\IPFO\Parties\Applicant;
use SNicholson\IPFO\ValueObjects\Citation;
use SNicholson\IPFO\Parties\Inventor;
use SNicholson\IPFO\Parties\Party;

class USPTODataMapper extends DataMapper implements DataMapperInterface
{

    public function mapData()
    {
        $result = new IPRight();
        $this->getGrant($result);
        $this->getApplication($result);
        $this->getParties($result);
        $this->getTitles($result);
        $this->getCitations($result);
        return $result;
    }

    protected function getGrant(IPRight &$result)
    {
        $re = "/(?i)#h2[\\S\\s]*?<\\/b>([\\S\\s]*?)<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $result->setGrantNumber(trim($matches[1]));

        $re = '/(?i)<b>[\s]*(\S{0,10}? \d{1,2}?, \d{4}?)[\s]*<\/b>/';
        preg_match($re, '        <TD align="right" width="50%"> <b>
                August 2, 2005
            </b></TD>', $matches);
        if(!empty($matches)) {
            $result->setGrantDate(DateTime::createFromFormat('F d, Y', $matches[1])->format('Y-m-d'));
        }
        $result->setGrantCountry('US');
    }

    protected function getApplication(IPRight &$result)
    {
        //Application number
        $re = "/(?i)Appl\\. No\\.:[\\S\\s]{0,150}<b>([\\S\\s]{0,15})<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $result->setApplicationNumber(trim($matches[1]));

        //Application Date
        $re = "/(?i)Filed:[\\S\\s]{0,150}<b>([\\S\\s]{0,20})<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);

        $result->setApplicationDate(DateTime::createFromFormat('F d, Y', $matches[1])->format('Y-m-d'));
        $result->setApplicationCountry('US');
    }

    protected function getParties(IPRight &$result)
    {
        //Inventors, get a list of all of them
        $re = "/(?i)<tr>[\\s\\S]*?Inventors:([\\s\\S]*?<\\/tr>)/";
        preg_match($re, $this->unmappedResponse, $matches);
        $inventorsHTML = trim($matches[1]);

        $re = "/(?i)<b>([\\s\\S]*)<\\/b>/";
        preg_match_all($re, $inventorsHTML, $matches);

        if (!empty($matches[1][0])) {
            $splitInventors = explode('</B>', $matches[1][0]);
            $inventors = new Party();
            foreach ($splitInventors as $sequence => $inventor) {
                $newInv = new Inventor();
                $re = "/(?i)(.*)<B>,/";
                $inventor = trim(preg_replace($re, '', $inventor));
                $newInv->setName($inventor);
                $newInv->setSequence($sequence + 1);
                $inventors->addMember($newInv);
            }
            $result->setInventors($inventors);
        }

        //Applicant
        $re = "/(?i)Assignee:[\\s\\S]*?<b>([\\s\\S]*?)<\\/b>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $appObj = new Applicant();
        $appObj->setName(trim($matches[1]));
        $appObj->setSequence(1);
        $applicants = new Party();
        $applicants->addMember($appObj);
        $result->setApplicants($applicants);
    }

    protected function getTitles(IPRight &$result)
    {
        $re = "/(?i)<FONT size=\\\"\\+1\\\">([\\S\\s]*)<\\/FONT>/";
        preg_match($re, $this->unmappedResponse, $matches);
        $result->setEnglishTitle(trim($matches[1]));
    }

    protected function getCitations(IPRight &$result)
    {
        //Patent Citations
        $re = "/U.S. Patent Documents([\\s\\S]*)Foreign/";
        preg_match($re, $this->unmappedResponse, $matches);
        if (!empty($matches[1])) {
            $re = "/<tr>([\\s\\S]*)\\/tr>/iU";
            preg_match_all($re, $matches[1], $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $row) {
                    $re = "/<td[\\s\\S]*<a.*>(?<number>[\\s\\S]*)<\\/a><\\/td><td.*>(?<date>[\\s\\S]*)<\\/td><td.*>(?<Author>[\\s\\S]*)<\\/td>/iU";
                    preg_match($re, $row, $matches);
                    if (!empty($matches['number'])) {
                        $result->addCitation(Citation::patent($matches['number'], 'US', '', trim($matches['date'])));
                    }
                }
            }
        }
        $re = "/Foreign Patent Documents([\\s\\S]*)Claims/";
        preg_match($re, $this->unmappedResponse, $matches);
        if (!empty($matches[1])) {
            $re = "/<tr>([\\s\\S]*)\\/tr>/iU";
            preg_match_all($re, $matches[1], $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $row) {
                    //Find patent citations
                    $re = "/<td[\\s\\S]*<\\/td><td[\\s\\S]*>(?<text>[\\s\\S]*)<\\/td><td[\\s\\S]*><\\/td><td[\\s\\S]*>(?<date>[\\s\\S]*)<\\/td><td[\\s\\S]*><\\/td><td.*>(?<country>[\\s\\S]*)<\\/td>/iU";
                    preg_match($re, $row, $matches);
                    if (!empty($matches['text'])) {
                        $result->addCitation(Citation::nonPatentLiterature($matches['text'], '', trim($matches['country']), trim($matches['date'])));
                    }
                    //Find npl citations
                }
            }
        }
    }
}
