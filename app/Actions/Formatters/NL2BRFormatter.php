<?php

namespace App\Actions\Formatters;

use App\Contracts\TextFormatter;

class NL2BRFormatter implements TextFormatter
{
    protected string $text;

    /**
     * @param  string  $text
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    public function __toString()
    {
        return nl2br($this->text);
    }
}
