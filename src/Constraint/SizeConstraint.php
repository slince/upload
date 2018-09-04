<?php

/*
 * This file is part of the slince/upload package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Upload\Constraint;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SizeConstraint implements ConstraintInterface
{
    /**
     * Maximum file size
     * @var int
     */
    protected $maxSize;

    /**
     * Minimum file size
     * @var int
     */
    protected $minSize;

    /**
     * Limit file size (use "B", "K", M", or "G")
     * @param int|string|null $minSize
     * @param int|string|null $maxSize
     */
    public function __construct($minSize = null, $maxSize = null)
    {
        if ($minSize !== null) {
            $this->minSize = static::humanReadableToBytes($minSize);
        }
        if ($maxSize !== null) {
            $this->maxSize = static::humanReadableToBytes($maxSize);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(UploadedFile $file)
    {
        $size = $file->getSize();
        $result = ($this->maxSize !== null || $size <= $this->maxSize)
            && ($this->minSize !== null || $size >= $this->minSize);
        return true;
    }


    /**
     * Convert human readable file size (e.g. "10K" or "3M") into bytes
     * @link https://github.com/brandonsavage/Upload/blob/master/src/Upload/File.php#L446
     * @param  string $input
     * @return int
     */
    public static function humanReadableToBytes($input)
    {
        $number = (int)$input;
        $units = array(
            'b' => 1,
            'k' => 1024,
            'm' => 1048576,
            'g' => 1073741824
        );
        $unit = strtolower(substr($input, -1));
        if (isset($units[$unit])) {
            $number = $number * $units[$unit];
        }
        return $number;
    }
}
