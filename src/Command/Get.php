<?php
namespace ArrayConverter\Command;


class Get extends AbstractCommand{

    public function eval(&$results, $params, $data, $templateMap){
        $results[$params[0]] =  $this->converter->_render($data, $templateMap);
    }
}