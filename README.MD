# LiveSelect (WORK IN PROGRESS)

A customizable dropdown component made with Laravel, Alpine, Livewire and TailwindCSS.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Usage](#usage)
- [Support](#support)
- [Contributing](#contributing)
- [License](#license)


## Requirements

1. PHP >= 7.4 - We use Typed Properties
2. Laravel >= 8.0
3. Livewire >= 2.3
4. Alpine.js >= 2.8
5. TailwindCSS >= 2.0

All those requeriments can be easily scaffolded by using [Laravel Jetstream](https://jetstream.laravel.com/1.x/introduction.html).

## Installation

```sh
composer require nickstarlight/live-select
```

## Usage

On the parent component:

1. On the component class:
```php
use LiveSelect\Traits\WithLiveSelect; // The LiveSelect trait required for component communication

class YourComponent extends Component 
{
    use WithLiveSelect; // Include the LiveSelect trait

    public $yourModel; // Create a model
    public $data = [
        [
            'id' => 1,
            'name' => 'Leroy Jenkins'
        ]
    ]; // Define your data
}
```

2. On the component view:
```php
<div>
    <livewire:live-select
        :multi="false"                  // Multiselection mode                                         - Defaults to false 
        :search="true"                  // Allows the user to filter values on the dropdown            - Defaults to false
        :description="'Select a value'" // The input label                                             - Defaults to null
        :options="$data"                // The options that will be rendered on the dropdown           - Required
        :label="'name'"                 // The key/value pair to be used as the options label          - Required
        :value="'id'"                   // The key/value pair to be used as the options value          - Required
        :model="'yourModel'"            // Your parent component public variable name for data binding - Required
    >
</div>
```

## Support

Please [open an issue](https://github.com/NickStarlight/LiveSelect/issues/new) for support.

## Contributing

The project is currently under the initial structuration, if you believe you can contribute, please, open an issue for discussion.

## License

Copyright (c) 2021 Nickolas Gomes Moraes

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.