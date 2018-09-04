<?php

/*
 * This file is part of the slince/upload package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Upload\Filesystem;

class Temp extends Local
{
    public function __construct()
    {
        parent::__construct(sys_get_temp_dir());
    }
}