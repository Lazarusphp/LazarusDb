<?php
namespace LazarusPhp\DatabaseManager;

interface ConfigInterface
{
    public function setType();
    public function setHostname();
    public function setUsername();
    public function setPassword();
    public function setDbname();

}