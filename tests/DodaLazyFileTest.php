<?php

use PHPUnit\Framework\TestCase;
use cwmoss\doda;

require_once("DodaBase.php");

final class DodaLoazyFileTest extends DodaBase
{
    public function setup(): void
    {
        $this->domain = new doda(__DIR__.'/data/lazy');
        $this->domain->load();
    }
}
