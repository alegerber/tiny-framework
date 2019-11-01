<?php

declare(strict_types=1);

namespace Tiny\Http;

class Response
{
    /**
     * @var string $content
     */
    private string $content;

    /**
     * @var int $status_code
     */
    private int $status_code;

    /**
     * @var string $header
     */
    private string $header;

    /**
     * Response constructor.
     *
     * @param string $content
     * @param int $status_code
     * @param string $header
     */
    public function __construct(string $content, int $status_code = 200, string $header = null)
    {
        $this->status_code = $status_code;
        $this->content = $content;
        $this->header = $header;
    }

    public function __toString()
    {
        $header = sprintf('HTTP/1.1 %s OK', (string) $this->status_code);

        if(null !== $this->header){
            $header .= $this->header;
        }

        header($header);

        return $this->content;
    }
}
