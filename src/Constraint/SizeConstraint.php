<?php

namespace Slince\Upload\Constraint;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class SizeConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    protected string $errorMessageTemplate = 'File size should between {minSize} and {maxSize}';

    /**
     * Maximum file size
     * @var int|null
     */
    protected ?int $maxSize;

    /**
     * Minimum file size
     * @var int|null
     */
    protected ?int $minSize;

    /**
     * Limit file size (use "B", "K", M", or "G")
     * @param int|string|null $minSize
     * @param int|string|null $maxSize
     */
    public function __construct(int|string $minSize = null, int|string $maxSize = null)
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
    public function validate(UploadedFile $file): bool
    {
        $size = method_exists($file, 'getClientSize') ?
            $file->getClientSize() : $file->getSize();

        return ($this->maxSize === null || $size <= $this->maxSize)
            && ($this->minSize === null || $size >= $this->minSize);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage(UploadedFile $file): string
    {
        return str_replace(['{minSize}', '{maxSize}'], [$this->minSize, $this->maxSize], $this->errorMessageTemplate);
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorMessage(string $messageTemplate): void
    {
        $this->errorMessageTemplate = $messageTemplate;
    }

    /**
     * Convert human-readable file size (e.g. "10K" or "3M") into bytes
     * @link https://github.com/brandonsavage/Upload/blob/master/src/Upload/File.php#L446
     * @param  string $input
     * @return int
     */
    public static function humanReadableToBytes(string $input): int
    {
        $number = (int)$input;
        $units = [
            'b' => 1,
            'k' => 1024,
            'm' => 1048576,
            'g' => 1073741824
        ];
        $unit = strtolower(substr($input, -1));
        if (isset($units[$unit])) {
            $number *= $units[$unit];
        }
        return $number;
    }
}
