# array-transform-php

# /!\ Work in progress... /!\ 

## Usage: 
### Convert array to xml 
```php
$converter
    ->setTemplate('
     {% for user in users %}
    <user> 
        <firstname>{{user.firstname}}</firstname>
        <lastname>{{user.lastname}}</lastname>
    </user>
    {% endfor %}
    ')
    ->render([
        'user' => [
            'firstname' => 'John',
            'lastname' => 'Snow'
        ]
    ]);
```
## TODO from array 
You can just typecast it ? 
# Set the template engine

## Synthaxe

```php
[
    'KeyDestination' => 'KeySource'
]
```
### @get()

#### Accessing keys
```php
"keyDestination" => "@get(key)"
```

#### Multiple key into one
```php
"keyDestination" => "My name is @get(firstname) @get(lastname)"
```

#### Nested keys

```php
"keyDestination" => "My name is @get(payload[0].user.firstname)"
```

### @forEach()

```php
"keyDestination" => '@forEach(keyToAnIterate, "Hello @get(firstname) @get(lastname)" )'
```

### @compute()

```php
"keyDestination" => '@compute(10 * @get(data.a) / @get(data.b))'
```
### @regex()

TODO

### @implode(keyToIterate, renderexpression , glue)
```php
"keyDestination" => '@compute(10 * @get(data.a) / @get(data.b))'
```

### @customCommand() 

You can define your own custom command: 
```php
"keyDestination" => '@customCommand(param1, param2)'
```


### Custom transformer with a function
```php
"keyDestination" => function ($data){
    // Do your magic...
    return $result;
}
```

## Creating custom transformer

```php
class CustomTransformer extends Transformer{

    public function apply(){
        // Access data to convert with $this->data
        // Access converter data: $this->result
        
        return 'my result';
    }
}
```


## Example
```php
$converter = new Converter([
    'payload' => [
        0 => [
            'user' => [
                'firsname' => 'Torstein',
                'lastname' => 'Horgmo'
            ]
        ],
        1 => [
            'user' => [
                'firsname' => 'Marcus',
                'lastname' => 'Kleveland'
            ]
        ]
    ],
    'extra': [
        'total': 20,
        'page': 1,
        'perPage': 10
    ]
]);
$convert->convert([
    'messages' => '@forEach("payload", "Hello {user.firstname} {user.lastname}!")'
    'extra.message' => '{extra.page}/@devide(@get("extra.total"), @get("extra.perPage")) ({extra.perPage} page(s))'
]);

// Will results in
[
    'messages' => [
        'Hello Torstein Horgmo!',
        'Hello Marcus Kleveland!'
    ],
    'extra' => [
        'message' => '1/2 (2 pages(s))'
    ]
]
```

## Inspiration

- Blade compiler (source https://github.com/illuminate/view/blob/master/Compilers/BladeCompiler.php)
- Twig https://twig.symfony.com/
## Real usage