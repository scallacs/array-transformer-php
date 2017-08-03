<?php
namespace ArrayConverter\Command;

class Value extends AbstractCommand
{

    public function eval(&$results, $params, $data, $templateMap)
    {
        $template = $params[0];
        $key = $this->converter->_render($data, $template);
        if (!is_string($key)){
            throw new \Exception('Command evaluation should return a string');
        }
        if (isset($results[$key])){
            print_r($results[$key]);die();
        }
            print_r($results);
            print_r($key);
        $results[$key] = (isset($results) && is_array($results) ? $results: []) + $this->converter->_render($data, $templateMap);
    }
}
