# Kirby Template Hooks

Kirby template hooks allow you to manage hooks for page and file templates more easily.

## Installation

### Installation with composer

```ssh
composer require afbora/kirby-template-hooks
```

### Add as git submodule

```ssh
git submodule add https://github.com/afbora/kirby-template-hooks.git site/plugins/kirby-template-hooks
```

## Usage

### Pages

**Usage:** `page.TEMPLATE_NAME.ACTION_NAME:before/after`

```php
<?php

return [
    'hooks' => [
        'page.product.update:after' => function ($newPage, $oldPage) {
            // your code goes here
        }
    ]
];

```

### Files

**Usage:** `file.TEMPLATE_NAME.ACTION_NAME:before/after`

```php
<?php

return [
    'hooks' => [
        'file.cover.update:after' => function ($newFile, $oldFile) {
            // your code goes here
        }
    ]
];

```
