<?php

namespace Atproto\Collections;

use Atproto\Contracts\Lexicons\App\Bsky\RichText\FacetContract;
use GenericCollection\GenericCollection;

class FacetCollection extends GenericCollection
{
    public function __construct(iterable $collection = [])
    {
        parent::__construct(fn ($item) => $item instanceof FacetContract, $collection);
    }
}
