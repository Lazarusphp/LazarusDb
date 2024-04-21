<?php
namespace Lazarus\LazarusDb\Interfaces;

interface DbControl
{
    private function Hostname();
    private function Username();
    private function Password();
    private function Dbname();
    private function dsn();
    public function Open_Connection
    
}