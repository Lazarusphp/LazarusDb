<?php
namespace LazarusPhp\LazarusDb\SchemaBuilder\Interfaces;

interface SchemaLoaderInterface
{

    public function up(string $table):void;
    public function down(string $table):void;

}