<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\Interfaces;

interface SchemaLoaderInterface
{

    public function create(string $table):void;
    public function drop(string $table):void;

}