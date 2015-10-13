Knit Bundle
===========

Knit ties your PHP objects with your database of choice in a simple way. Read more about Knit in its
[repository](https://github.com/michaldudek/Knit).

This is a Symfony2 Bundle that integrates Knit with the framework.

# Installation

Require the bundle:

    $ composer require michaldudek/knit-bundle

Enable the bundle in your Kernel:

    <?php

    // ...

        public function registerBundles()
        {
            $bundles = [
                // ...

                new Knit\Bundle\KnitBundle(),
            ];
            // ...
        }

# Configuration

Add `knit` section to your `config.yml`.

There are three available settings and all of them are just names of services you want
injected to the main `Knit\Knit` class (those will serve as defaults for all 
repositories).

    knit:
        # required, default store
        store: [store.service_name]

        # optional, default data mapper, "knit.data_mapper.array_serializer" by default
        data_mapper: [data_mapper.service_name]

        # optional, event dispatcher used, "event_dispatcher" by default
        event_dispatcher: [event_dispatcher.service_name]

## Configuring a Store

As you can see, for Knit, a store is nothing more than a dependency that needs to be
injected. This gives you power to configure your stores in any way you want.

For convenience, KnitBundle registers two dependencies for the two stores it implements
so far: `knit.store.doctrine_dbal.criteria_parser` and `knit.store.mongodb.criteria_parser`.
It also registers two parameters for easier resolution of store classes:
`%knit.store.doctrine_dbal.class%` and `%knit.store.mongodb.class%`.

Using these two aspects you can easily configure your data stores and register them in
the container:

    services:

        mysql_store:
            class: %knit.store.doctrine_dbal.class%
            arguments:
                - driver: pdo_mysql
                  user: %db.username%
                  password: %db.password%
                  host: %db.host%
                  dbname: %db.database%
                - @knit.store.doctrine_dbal.criteria_parser
                - @logger

        # or

        mongodb_store:
            class: %knit.store.mongodb.class%
            arguments:
                - hostname: %mongo.host%
                  database: %mongo.database%
                  username: %mongo.username%
                  password: %mongo.password
                - @knit.store.mongodb.criteria_parser
                - @logger

where all the connection parameters you have to register yourself, obviously.

Then in `config.yml` you can just specify:

    knit:
        store: mysql_store    # or mongodb_store

# Repositories

By convention, all repositories should be registered as services. You are going to inject
them into other services or controllers anyway, so for clarity KnitBundle doesn't
automagically creates them.

An example definition of a repository is like this:

    user.repository:
        parent: knit.repository
        arguments: [MyApp\User, "users"]

where 1st argument is the managed object class name and second a collection name.
Additional arguments are also allowed - see
[Knit documentation](https://github.com/michaldudek/Knit) for details on what they are.

# License

MIT, see [LICENSE.md](LICENSE.md).

Copyright (c) 2015 Michał Pałys-Dudek
