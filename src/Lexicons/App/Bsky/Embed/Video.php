<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\MediaContract;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\VideoInterface;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Collections\CaptionCollection;
use Atproto\Lexicons\Traits\Lexicon;

class Video implements VideoInterface, MediaContract
{
    use Lexicon;

    private Blob $file;
    private ?string $alt = null;
    private CaptionCollection $captions;
    private array $aspectRatio = [];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Blob $file)
    {
        if ("video/mp4" !== $file->mimeType()) {
            throw new InvalidArgumentException($file->path()." is not a valid video file.");
        }

        $this->file = $file;

        $this->captions = new CaptionCollection();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function jsonSerialize(): array
    {
        $result = array_filter([
            '$type' => $this->nsid(),
            'alt' => $this->alt() ?: null,
            'video' => $this->file,
            'aspectRatio' => $this->aspectRatio() ?: null,
            'captions' => $this->captions()->toArray() ?: null,
        ]);

        return $result;
    }

    public function alt(string $alt = null)
    {
        if (is_null($alt)) {
            return $this->alt;
        }

        $this->alt = $alt;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function aspectRatio(int $width = null, int $height = null)
    {
        if (is_null($width) && is_null($height)) {
            return $this->aspectRatio;
        }

        if ($width < 1 || $height < 1) {
            throw new InvalidArgumentException("Width and height must be greater than 1");
        }

        $this->aspectRatio = [
            'width' => $width,
            'height' => $height
        ];

        return $this;
    }

    public function captions(CaptionCollection $captions = null)
    {
        if (is_null($captions)) {
            return $this->captions;
        }

        $this->captions = $captions;

        return $this;
    }
}
