<?php
namespace ArrayConverter\Command;


class Iterate extends AbstractCommand{

    public function eval(&$results, $params, $data, $templateMap){
        // TODO check at least one parameter
        $key = $params[0];
        $parts = explode(' as ', $key);
        // print_r($parts);
        $collectionName = $parts[0];
        $variableName = $parts[1];
        foreach ($data[$collectionName] as $d){
            $context = [
                '_iterate' => [
                    $variableName => $d
                ],
            ] + $data;

            $results[] = $this->converter->_render($context, $templateMap);
        }
    }
}