<?php

namespace ArrayConverter\Test;

use PHPUnit\Framework\TestCase;

class StackOverflowTest extends TestCase
{
    /**
    * https://stackoverflow.com/questions/9791777/organizing-an-array-into-a-nested-array-with-php
    */
    public function testConvert()
    {
        $input = [
            array("type" => "off-site", "title" => "aaa", "nid" => "11"),
            array("type" => "off-site", "title" => "bbb", "nid" => "22"),
            array("type" => "installation", "title" => "ccc", "nid" => "33"),
            array("type" => "opening", "title" => "ddd", "nid" => "44"),
            array("type" => "opening", "title" => "eee", "nid" => "55"),
            array("type" => "opening", "title" => "fff", "nid" => "66")
        ];
        $map = [
            '@iterate($ as d)' => [
                '@value({{d.type}})' => [
                    '@value({{__context__.index}})' => [
                        'title' => '{{d.title}}',
                        'nid' => '{{d.nid}}'
                    ]
                ]
            ]
        ];

        $converter = new \ArrayConverter\ConverterEngine();
        $converter
        ->addCommand('value', new \ArrayConverter\Command\Value())
        ->addCommand('iterate', new \ArrayConverter\Command\Iterate())
        ->setTemplateEngine(new \ArrayConverter\TemplateEngineAdapter\TwigAdapter())
        ->setTemplateMap($map);

        print_r($converter->render($input));
    }
}
