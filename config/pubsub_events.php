<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default
    |--------------------------------------------------------------------------
    |
    | The default pub-sub connection to use for event handling.
    |
    | This can be any adapter supported by the "laravel-pubsub" package
    | https://github.com/Superbalist/laravel-pubsub
    |
    | Supported: "/dev/null", "local", "redis", "kafka", "gcloud"
    |
    | If null, the default connection defined by the "laravel-pubsub" config
    | will be used.
    |
    */

    'default' => env('PUBSUB_EVENTS_CONNECTION'),

    /*
    |--------------------------------------------------------------------------
    | Translator
    |--------------------------------------------------------------------------
    |
    | The translator used to translate an incoming message into an event.
    |
    | This must reference a container binding which resolves to a
    | MessageTranslatorInterface.
    |
    | You can use the following pre-registered bindings, or use your own binding
    | for a custom translator.
    |
    | * pubsub.events.translators.simple
    | * pubsub.events.translators.topic
    | * pubsub.events.translators.schema
    |
    */

    'translator' => env('PUBSUB_EVENTS_TRANSLATOR', 'pubsub.events.translators.simple'),

    /*
    |--------------------------------------------------------------------------
    | Validator
    |--------------------------------------------------------------------------
    |
    | The validator is an optional component used to validate an incoming event.
    |
    | This must reference a container binding which resolves to a
    | EventValidatorInterface.
    |
    | You can use the following pre-registered bindings, or use your own binding
    | for a custom validator.
    |
    | * pubsub.events.validators.json_schema
    |
    */

    'validator' => env('PUBSUB_EVENTS_VALIDATOR'),

    /*
    |--------------------------------------------------------------------------
    | Validators
    |--------------------------------------------------------------------------
    |
    */

    'validators' => [

        'json_schema' => [

            'loaders' => [

                'array' => [

                    // optional binding name
                    // eg: array
                    // if null, the name of the loader will be used
                    // pubsub.events.validators.json_schema.loaders.(binding)
                    'binding' => 'array',

                    // optional schema prefix
                    // eg: array (translates to array:// in the scheme uri)
                    // if null, the name of the loader will be used
                    'prefix' => 'array',

                    // array of schemas which will be auto-loaded
                    // you can also add your schemas using your own service provider
                    // eg:
                    /*
                    $schemas = app('pubsub.events.validators.json_schema.loaders.array.schemas');
                    $schemas['key'] = [
                        // schema ...
                    ];
                    */

                    'schemas' => [

                        // key => schema

                        // example schema
                        /*
                        'events/(topic)/(event)/1.0.json' => json_encode([
                            '$schema' => 'http://json-schema.org/draft-04/schema#',
                            'title' => 'My Schema',
                            'type' => 'object',
                            'properties' => [
                                // ...
                            ],
                            'required' => [
                                // ...
                            ],
                        ]),
                        */

                    ],

                ],

            ],

        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Attribute Injectors
    |--------------------------------------------------------------------------
    |
    | An attribute injector automatically injects attributes into events
    | when events are dispatched.
    | For example, you may wish to have the date, hostname, user data injected
    | into each and every event which is fired.
    |
    | Please see https://github.com/Superbalist/php-event-pubsub for a full list
    | of supported injectors.
    |
    */

    'attribute_injectors' => [

        /*
        \Superbalist\EventPubSub\AttributeInjectors\DateAttributeInjector::class,
        \Superbalist\EventPubSub\AttributeInjectors\HostnameAttributeInjector::class,
        \Superbalist\EventPubSub\AttributeInjectors\Uuid4AttributeInjector::class,
        function () {
            return [
                'key' => 'user_id',
                'value' => 1234,
            ];
        }
        */

    ],

];
