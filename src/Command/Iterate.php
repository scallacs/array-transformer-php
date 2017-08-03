<?php
namespace ArrayConverter\Command;

class Iterate extends AbstractCommand
{

    public function eval(&$results, $params, $data, $templateMap)
    {
        $key = $params[0];
        $parts = explode(' as ', $key);
        if (count($parts) !== 2) {
            throw new \Exception('Invalid argument: ' . $key);
        }
        // print_r($parts);
        if ($parts[0] === '$') {
            $collection = $data;
        } else {
            if (!isset($data[$parts[0]])) {
                throw new \Exception('Key "'.$parts[0].'" does not exists');
            }
            $collection = $data[$parts[0]];
        }
        $variableName = $parts[1];
        $i = 0;
        foreach ($collection as $d) {
            $context = [
                '__original__' => $data,
                '__context__' => [
                    'index' => $i++
                ],
                 $variableName => $d,
            ];

            $result = $this->converter->_render($context, $templateMap);
            if (is_array($result)) {
                $results += $result;
                // $results = array_merge($results, $result);
            } else {
                $results[] = $result;
            }
        }
        return $results;
    }
}
