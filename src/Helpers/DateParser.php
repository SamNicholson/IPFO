<?php

namespace SNicholson\IPFO\Helpers;

class DateParser
{
    public static function EPO($date)
    {
        return substr($date, 0, 4) . '-' . substr($date, 4, 2) . '-' . substr($date, 6, 2);
    }
}
