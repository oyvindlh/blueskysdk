<?php

namespace Atproto\Lexicons\App\Bsky\Embed\Collections;

use Atproto\Exceptions\InvalidArgumentException;

trait EmbedCollection
{
    /**
     * @throws InvalidArgumentException
     */
    public function __construct(iterable $collection = [])
    {
        $this->collection = $collection;

        try {
            parent::__construct($this->validator(), $collection);
        } catch (\TypeError|\GenericCollection\Exceptions\InvalidArgumentException $e) {
            $this->throw($e);
        }
    }

    /**
     * @throws InvalidArgumentException
     */
    private function throw($exception): void
    {
        throw new InvalidArgumentException(
            str_replace(
                "::".__NAMESPACE__."\{closure}()",
                "",
                $exception->getMessage()
            ),
            0,
            $exception
        );
    }

    abstract protected function validator(): \Closure;
}
