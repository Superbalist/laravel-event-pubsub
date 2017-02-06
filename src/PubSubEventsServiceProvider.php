<?php

namespace Superbalist\LaravelEventPubSub;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use League\JsonGuard\Dereferencer;
use League\JsonGuard\Loader;
use League\JsonGuard\Loaders\ArrayLoader;
use Superbalist\EventPubSub\EventManager;
use Superbalist\EventPubSub\EventValidatorInterface;
use Superbalist\EventPubSub\MessageTranslatorInterface;
use Superbalist\EventPubSub\Translators\SchemaEventMessageTranslator;
use Superbalist\EventPubSub\Translators\SimpleEventMessageTranslator;
use Superbalist\EventPubSub\Translators\TopicEventMessageTranslator;
use Superbalist\EventPubSub\Validators\JSONSchemaEventValidator;
use Superbalist\LaravelPubSub\PubSubManager;
use Superbalist\LaravelPubSub\PubSubServiceProvider;
use Superbalist\PubSub\PubSubAdapterInterface;

class PubSubEventsServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/pubsub_events.php' => config_path('pubsub_events.php'),
        ]);
    }

    /**
     * Register bindings in the container.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/pubsub_events.php', 'pubsub_events');

        $this->app->register(PubSubServiceProvider::class);

        $this->app->bind('pubsub.events.connection', function ($app) {
            // we'll use the connection name configured in the 'default' config setting from the 'pubsub_events'
            // config
            // if this value isn't set, we'll default to that from the 'pubsub' package config
            $config = $app['config']['pubsub_events'];
            $manager = $app['pubsub']; /* @var PubSubManager $manager */
            return $manager->connection($config['default']);
        });

        $this->app->bind('pubsub.events.translator', MessageTranslatorInterface::class);

        $this->app->bind(MessageTranslatorInterface::class, function ($app) {
            $config = $app['config']['pubsub_events'];
            $binding = $config['translator'];
            return $app[$binding];
        });

        $this->app->bind('pubsub.events.validator', EventValidatorInterface::class);

        $this->app->bind(EventValidatorInterface::class, function ($app) {
            $config = $app['config']['pubsub_events'];
            $binding = $config['validator'];
            // a validator is optional
            // if nothing is set, we don't try resolve it
            return $binding === null ? null : $app[$binding];
        });

        $this->registerTranslators();
        $this->registerValidators();

        $this->app->singleton('pubsub.events', function ($app) {
            $adapter = $app['pubsub.events.connection']; /** @var PubSubAdapterInterface $connection */
            $translator = $app['pubsub.events.translator']; /** @var MessageTranslatorInterface $translator */
            $validator = $app['pubsub.events.validator']; /** @var EventValidatorInterface $validator */
            $injectors = [];
            $config = $app['config']['pubsub_events'];
            foreach ($config['attribute_injectors'] as $binding) {
                if (is_callable($binding)) {
                    $injectors[] = $binding;
                } else {
                    // resolve binding from container
                    $injectors[] = $app[$binding];
                }
            }

            return new EventManager($adapter, $translator, $validator, $injectors);
        });
    }

    /**
     * Register translators in the container.
     */
    protected function registerTranslators()
    {
        $this->app->bind('pubsub.events.translators.simple', function () {
            return new SimpleEventMessageTranslator();
        });

        $this->app->bind('pubsub.events.translators.topic', function () {
            return new TopicEventMessageTranslator();
        });

        $this->app->bind('pubsub.events.translators.schema', function () {
            return new SchemaEventMessageTranslator();
        });
    }

    /**
     * Register validators in the container.
     */
    protected function registerValidators()
    {
        $this->app->singleton('pubsub.events.validators.json_schema.loaders.array.schemas', function ($app) {
            $config = $app['config']['pubsub_events'];
            $schemas = $config['validators']['json_schema']['loaders']['array']['schemas'];
            return collect($schemas);
        });

        $this->app->bind('pubsub.events.validators.json_schema.loaders.array', function ($app) {
            $schemas = $app['pubsub.events.validators.json_schema.loaders.array.schemas']; /* @var Collection $schemas */
            return new ArrayLoader($schemas->all());
        });

        $this->app->bind('pubsub.events.validators.json_schema', function ($app) {
            $dereferencer = $app['pubsub.events.validators.json_schema.dereferencer']; /* @var Dereferencer $dereferencer */
            return new JSONSchemaEventValidator($dereferencer);
        });

        $this->app->bind('pubsub.events.validators.json_schema.dereferencer', function ($app) {
            $dereferencer = new Dereferencer();

            $config = $app['config']['pubsub_events'];

            foreach ($config['validators']['json_schema']['loaders'] as $name => $params) {
                $name = array_get($params, 'binding', $name);
                $binding = sprintf('pubsub.events.validators.json_schema.loaders.%s', $name);

                $prefix = array_get($params, 'prefix', $name);

                $loader = $app[$binding]; /* @var Loader $loader */

                $dereferencer->registerLoader($loader, $prefix);
            }

            return $dereferencer;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'pubsub.events',
            'pubsub.events.connection',
            'pubsub.events.translator',
            'pubsub.events.translators.simple',
            'pubsub.events.translators.topic',
            'pubsub.events.translators.schema',
            'pubsub.events.validator',
            'pubsub.events.validators.json_schema',
            'pubsub.events.validators.json_schema.dereferencer',
            'pubsub.events.validators.json_schema.loaders.array',
            'pubsub.events.validators.json_schema.loaders.array.schemas',
        ];
    }
}
