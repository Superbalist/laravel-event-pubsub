# Changelog

# Changelog

## 3.0.0 - 2017-07-17

* Bump up to superbalist/php-event-pubsub ^4.0
* Add container binding for EventManager::class
* Change 'pubsub.events' binding to no longer be a singleton and to alias to EventManager::class
* Add new 'throw_validation_exceptions_on_dispatch' config option and PUBSUB_EVENTS_THROW_VALIDATION_EXCEPTIONS_ON_DISPATCH env var
* Add new 'translate_fail_handler' config option and default callable to dispatch a TranslationFailureEvent event
* Add new 'listen_expr_fail_handler' config option and default callable to dispatch a ListenExprFailureEvent event
* Add new 'validation_fail_handler' config option and default callable to dispatch a ValidationFailureEvent event

## 2.0.1 - 2017-05-16

* Allow for php-event-pubsub ^3.0

## 2.0.0 - 2017-02-02

* Update `superbalist/php-event-pubsub` to ^2.0
* Change `pubsub.events` to resolve as a singleton
* Added support for "Attribute Injectors"
* Added new `attribute_injectors` config key

## 1.0.0 - 2017-01-30

* Initial release