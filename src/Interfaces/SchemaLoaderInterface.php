<?php
namespace LazarusPhp\LazarusDb\Interfaces;

interface SchemaLoaderInterface
{

    public function create(string $table):void;
    public function modify(string $table):void;
    public function drop(string $table):void;

}