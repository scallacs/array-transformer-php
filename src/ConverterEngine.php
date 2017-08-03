<?php

namespace ArrayConverter;
use \ArrayConverter\TemplateEngineAdapter\AdapterInterface;

/**
 * Description of Converter
 *
 * @author Stephane
 */
class ConverterEngine
{

    /**
    * @var AdapterInterface
    */
    protected $templateEngine;

    protected $templateMap;

    protected $commands = [];
    
    function __construct()
    {
    }

    public function addCommand($id, $cmd)
    {
        $cmd->setConverterEngine($this);
        $this->commands[$id] = $cmd;
        return $this;
    }

    public function setTemplateMap(array $map): ConverterEngine
    {
        $this->templateMap = $map;
        return $this;
    }

    public function setTemplateEngine(AdapterInterface $templateEngine): ConverterEngine
    {
        $this->templateEngine = $templateEngine;
        return $this;
    }
    /**
     * return array
     */
    public function render(array $data)
    {
        return $this->_render($data, $this->templateMap);
    }

    public function _render(array $data, $templateMap)
    {
        if (is_string($templateMap)) {
            return $this->templateEngine->setTemplate($templateMap)->render($data);
        } elseif (is_array($templateMap)) {
            $results = [];
            foreach ($templateMap as $key => $template) {
                // echo "Parsing key: ", $key, '\r\n';
                if ($key[0] === '@') {
                    // Extracting cmd id
                    $i = 1;
                    while ($i < strlen($key) && $key[$i] != '(') {
                        $i++;
                    }
                    $cmdId = substr($key, 1, $i-1);
                    if (!isset($this->commands[$cmdId])) {
                        throw new \ArrayConverter\Exception\InvalidCommandException($cmdId);
                    }
                    // Extracting parameters
                    $params = $this->extractParameters(\substr($key, $i+1));
                    $this->commands[$cmdId]->eval($results, $params, $data, $template);
                } else {
                    if (is_array($template)) {
                        $result = $this->_render($data, $template);
                    } elseif (is_callable($template)) {
                        $result = $template($data);
                    } else {
                        $result = $this->templateEngine->setTemplate($template)->render($data);
                    }
                    if (is_array($result)) {
                        $results[$key] = ((isset($results[$key]) && is_array($results[$key])) ? $results[$key]: []) + $result;
                    } else {
                        $results[$key] = $result;
                    }
                }
                    // $results[$key] = $this->templateEngine->render($data);
            }
            return $results;
        } else {
            throw new \Exception('Invalid argument');
        }
    }

    public function extractParameters($str)
    {
        $results = [];
        $i = 0;
        while ($i < strlen($str) && $str[$i] != ')') {
            $value = '';
            while ($i < strlen($str) && $str[$i] != ',' && $str[$i] != ')') {
                $value .= $str[$i];
                $i++;
            }
            $results[] = $value;
        }
        return $results;
    }
}
