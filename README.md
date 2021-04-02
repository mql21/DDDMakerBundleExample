# DDDMakerBundle
A Symfony Bundle to easily create DDD elements from your console

**This project is currently under development.**

Current state: This project should be a [Symfony Bundle](https://symfony.com/doc/current/bundles.html). However, because it's still a work in progress, a full Symfony 5 app is being used to make the development of the bundle easier. While it's under development, bundle's code will reside in `lib/DDDMakerBundle/` directory.

## Goal

Normally when you work with Hexagonal Architechture or other clean architechtures, specially if working under a DDD approach, there's little room for "creativity". 
Because all of the layers are well defined, often times you find yourself copy-pasting classes (i.e. commands, queries, event subscribers, value objects...) from 
one context to another just to have a skeleton for the new class.

So, what if this copy-paste work could be automated? With DDDMakerBundle, you'll be able to create DDD elements from your console, just like Symfony does through 
the [Maker Bundle](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html).

For now, these are the elements that will be able to be created through this bundle:

- **Bounded context**: ddd:make:bounded-context

- **CQRS**
    - Command (*ddd:cqrs:make:command*) :hourglass_flowing_sand:
    - CommandHandler (*ddd:cqrs:make:command-handler*) :hourglass_flowing_sand:
    - Query (*ddd:cqrs:make:query*) :black_square_button:
    - QueryHandler (*ddd:cqrs:make:query-handler*) :black_square_button:

- **Domain**
    - Event (*ddd:domain:make:event*) :black_square_button:
    - Value Object (*ddd:domain:make:value-object*) :black_square_button:
- **Application**
    - EventSubscriber (*ddd:application:make:event-subscriber) :black_square_button:
    - UseCase (*ddd:application:make:use-case) :black_square_button:

- **List**
    - Bounded (*contexs ddd:list:bounded-contexts*) :black_square_button:
    - Commands (*ddd:cqrs:list:commands*) :black_square_button:
    - CommandHandlers (*ddd:cqrs:list:command-handlers*) :black_square_button:
    - Querys (*ddd:cqrs:list:queries*) :black_square_button:
    - QueryHandler (*ddd:cqrs:list:query-handlers*) :black_square_button:
    - Events (*ddd:domain:list:events*) :black_square_button:
    - EventSubscribers (*ddd:application:list:event-subscribers*) :black_square_button:
    - UseCases (*ddd:cqrs:list:use-cases*) :black_square_button:
   
:hourglass_flowing_sand: Someone is already working on this

:black_square_button: To do

:heavy_check_mark: Done
  ### Contributing
  
As you might have noticed, there are a lot of console commands to implement, so any help will be very welcome! If you want to contribute, just reach out opening an issue commenting the work you'd like to do so maybe I can give you some help!
