<?php

namespace LazarusPhp\LazarusDb\SchemaBuilder\Interfaces;

interface TableInterface
{
    // Define your method signatures here

    public function tinyInt(string $name);
    public function int(string $name);
    public function bigInt(string $name);
    public function varchar(string $name,int $value);
    public function text(string $name);
    public function mediumText(string $name);
    public function longText(string $name);
    public function date(string $name);
    public function dateTime(string $name);
}