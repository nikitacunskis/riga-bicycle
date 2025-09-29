<?php

namespace App\Exceptions;

use Exception;

class MetaPublishException extends Exception
{
    public function __construct(
        string $message,
        public $code = null,
        public ?string $type = null,
        public ?string $fbtraceId = null
    ) {
        parent::__construct($message, $code ?? 0);
    }

    public function context(): array
    {
        return [
            'code' => $this->code,
            'type' => $this->type,
            'fbtrace_id' => $this->fbtraceId,
        ];
    }
}
