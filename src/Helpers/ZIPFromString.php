<?php
/**
 * Created by PhpStorm.
 * User: samdev
 * Date: 18/11/14
 * Time: 10:41
 */

namespace SNicholson\IPFO\Helpers;


use SNicholson\IPFO\Exceptions\FileHandleException;

class ZIPFromString
{

    private $zip;
    private $tmpDir = '../../tmp/';
    private $filename;
    private $contents = array();

    public function __construct($fileString)
    {

        try {
            $this->filename = $this->tmpDir . date('Y-m-d-h:i:s') . rand(1000000, 2000000) . '.zip';

            file_put_contents($this->filename, $fileString);

            $this->zip = new \ZipArchive();
            $this->zip->open($this->filename);

            for ($i = 0; $i < $this->zip->numFiles; $i++) {
                $fp = $this->zip->getStream($this->zip->getNameIndex($i));
                if (!$fp) {
                    throw new FileHandleException('Failed to open contents of USPTO ZIP File');
                }
                $this->contents[$this->zip->getNameIndex($i)] = '';
                while (!feof($fp)) {
                    $this->contents[$this->zip->getNameIndex($i)] .= fread($fp, 8192);
                }
            }

            unlink($this->filename);

            return true;
        } catch (FileHandleException $e) {
            return $e->getMessage();
        }
    }

    public function getContents()
    {
        return $this->contents;
    }
}