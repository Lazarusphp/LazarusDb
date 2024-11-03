<?php
namespace LazarusPhp\DatabaseManager\Interfaces;

interface ConfigInterface
{
    public function setType();
    public function setHostname();
    public function setUsername();
    public function setPassword();
    public function setDbname();
    public function DetectFileType($filename);

}