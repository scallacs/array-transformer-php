<?php
namespace ArrayConverter\Command;

abstract class AbstractCommand
{
    
    protected $converter;
    
    public function setConverterEngine($engine){
        $this->converter = $engine;
        return $this;
    }
}
