<?php

namespace Atproto\Lexicons;

use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Lexicons\Traits\RequestBuilder;
use Atproto\Lexicons\Traits\RequestHandler;

class Request implements RequestContract
{
    use RequestHandler;
    use RequestBuilder;
}
