<?php
/**
 * Created by PhpStorm.
 * User: Sam
 * Date: 07/02/2016
 * Time: 01:08
 */

namespace SNicholson\IPFO\Parsers;


class Document
{
    private $content;
    private $filename;
    private $extension;

    public function __construct($content, $filename)
    {
        $this->content = $content;
        $this->filename = $filename;
        $parts = explode('.', $filename);
        $this->extension = $parts[count($parts) - 1];
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }
}