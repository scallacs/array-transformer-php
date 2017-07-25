# array-transform-php


```php
[
    'KeyDestination' => 'KeySource'
]
```

## Synthaxe

### Accessing keys {key}
```php
"keyDestination" => "{key}"
```

### Multiple key into one
```php
"keyDestination" => "My name is {firstname} {lastname}
```

### Nested keys

```php
"keyDestination" => "My name is {payload[0].user.firstname}"
```

### Custom transformer
```php
"keyDestination" => function ($data){
    // Do your magic...
    return $result;
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
    'messages' => new ForEach('{each(payload)}', 'Hello {user.firstname} {user.lastname}!')
    'extra.message' => '{extra.page}/{extra.total/extra.perPage} ({extra.perPage} page(s))'
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
