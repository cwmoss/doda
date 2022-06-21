<?php

use PHPUnit\Framework\TestCase;
use cwmoss\doda;

require_once("DodaBase.php");

final class DodaLazyFunParseTest extends DodaBase
{
    public function setup(): void
    {
        $functions = include(__DIR__.'/data/lazy/functions.php');
        $this->domain = new doda(__DIR__.'/data/fun', $functions);
        $this->domain->parse();
    }
}
