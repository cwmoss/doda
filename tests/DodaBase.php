<?php

use PHPUnit\Framework\TestCase;
use cwmoss\doda;

class DodaBase extends TestCase
{

    public function testSimple(): void
    {
        $domain = $this->domain;

        #var_dump($domain->data);
        $this->assertSame($domain->get('count.two'), 2);
        $this->assertSame($domain->get(['count', 'two']), 2);
        $this->assertNull($domain->get('count.xx'));
        $this->assertSame($domain->get('count.xx', 'xxx'), 'xxx');
        $this->assertSame($domain->get('countxx', 'xxx'), 'xxx');
        $this->assertNull($domain->get('countxx'));
        $this->assertSame($domain->get('countxx', ''), '');
        $this->assertNull($domain->get('count.x.y.z'));
        $this->assertSame($domain->get('count.empty_string', 'oo'), '');
        $this->assertNull($domain->get('count.empty_null', 'oo'));
        $this->assertNull($domain->get('count.null', 'oo'));
    }

    public function testImportYaml(): void
    {
        $domain = $this->domain;

        $this->assertSame($domain->get('categories.0'), 'sex');
        $this->assertNull($domain->get('categories.99'));
        $this->assertCount(3, $domain->get('categories'));
    }

  
    public function testImportIni(): void
    {
        $domain = $this->domain;

        $this->assertSame($domain->get('colors.red'), '#f00');
        $this->assertNull($domain->get('colors.gold'));
        $this->assertCount(3, $domain->get('colors'));
    }

    public function testImportDb(): void
    {
        $domain = $this->domain;

        $this->assertSame($domain->get('countries.de'), 'Germany');
        $this->assertNull($domain->get('countries.tk'));
        $this->assertCount(3, $domain->get('countries'));
    }

    public function testImportJson(): void
    {
        $domain = $this->domain;

        $this->assertSame($domain->get('cities.berlin'), 'germany');
        $this->assertNull($domain->get('cities.paris'));
        $this->assertSame($domain->get('cities.favourite', 'taiwan'), 'taiwan');
        $this->assertCount(3, $domain->get('cities'));
    }

    public function testImportPhp(): void
    {
        $domain = $this->domain;

        $this->assertSame($domain->get('fruits.banana'), 'yellow');
        $this->assertNull($domain->get('fruits.mango'));
        $this->assertCount(2, $domain->get('fruits'));
    }
}
