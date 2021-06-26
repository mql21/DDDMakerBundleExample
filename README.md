
# DDDMakerBundle
A Symfony Bundle to easily create DDD elements from your console

**This project is currently under development.**

Current state: This project is a [Symfony Bundle](https://symfony.com/doc/current/bundles.html). 
However, because it's still a work in progress, a full Symfony 5 app is being used to ease development process.
While it's under development, bundle's code will reside in `lib/DDDMakerBundle/` directory. Once it's finished
code will be released and published to packagist as a full, independent Symfony Bundle.

## Goal

Normally, when you work with Hexagonal Architecture or other clean architectures, specially if working under a DDD approach, there's little room for "creativity". 
Because all the layers are well-defined, often times you find yourself copy-pasting classes (i.e., commands, queries, event subscribers, value objects...) from 
one context to another just to have a skeleton for the new class.

So, what if this copy-paste work could be automated? With DDDMakerBundle, you'll be able to create DDD elements from your console, just like Symfony does through 
the [Maker Bundle](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html).

For now, these are the elements that will be able to be created through this bundle:

- **Bounded context** (*ddd:make:bounded-context*) :black_square_button:

- **CQS/CQRS**
    - Command (*ddd:cqs:make:command*) :heavy_check_mark:
    - CommandHandler (*ddd:cqs:make:command-handler*) :heavy_check_mark:
    - Query (*ddd:cqs:make:query*) :heavy_check_mark:
    - QueryHandler (*ddd:cqs:make:query-handler*) :heavy_check_mark:
    - Response  (*ddd:cqs:make:response*) :heavy_check_mark:

- **Domain**
    - Event (*ddd:domain:make:event*) :heavy_check_mark:
    - Value Object (*ddd:domain:make:value-object*) :heavy_check_mark:
- **Application**
    - EventSubscriber (*ddd:application:make:event-subscriber*) :heavy_check_mark:
    - UseCase (*ddd:application:make:use-case*) :heavy_check_mark:
- **Other**
    - Command to generate missing directories that are specified in config (*ddd:make:missing-directories*) :heavy_check_mark:

- **List**
    - Bounded (*contexs ddd:list:bounded-contexts*) :black_square_button:
    - Commands (*ddd:cqs:list:commands*) :black_square_button:
    - CommandHandlers (*ddd:cqs:list:command-handlers*) :black_square_button:
    - Queries (*ddd:cqs:list:queries*) :black_square_button:
    - QueryHandler (*ddd:cqs:list:query-handlers*) :black_square_button:
    - Events (*ddd:domain:list:events*) :black_square_button:
    - EventSubscribers (*ddd:application:list:event-subscribers*) :black_square_button:
    - UseCases (*ddd:cqs:list:use-cases*) :black_square_button:
   
:hourglass_flowing_sand: Someone is already working on this

:black_square_button: To do

:heavy_check_mark: Done
  ### Contributing
  
As you might have noticed, there are a lot of console commands to implement, so any help will be very welcome! If you want to contribute, just reach out opening an issue commenting the work you'd like to do so maybe I can give you some help!
