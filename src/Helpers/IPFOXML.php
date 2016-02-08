<?php

namespace SNicholson\IPFO\Helpers;

class IPFOXML extends \SimpleXMLElement
{
    /**
     * @param null $ns
     * @param bool $is_prefix
     * @return IPFOXML[]
     */
    public function children($ns = null, $is_prefix = false)
    {
        parent::children($ns, $is_prefix);
    }

    public function getValue()
    {
        if ($this->count()) {
            return (array) $this;
        }
        if (isset($this[0])) {
            return trim((string) $this);
        }
        return false;
    }
}
