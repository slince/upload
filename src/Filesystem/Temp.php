<?php

namespace Slince\Upload\Filesystem;

class Temp extends Local
{
    public function __construct()
    {
        parent::__construct(sys_get_temp_dir());
    }
}
