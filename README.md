# DDDMakerBundle
A Symfony Bundle to easily create DDD elements from your console

**This project is currently under development.**

## Goal

Normally when you work with Hexagonal Architechture or other clean architechtures, specially if working under a DDD approach, there's little room for "creativity". 
Because all of the layers are well defined, often times you find yourself copy-pasting classes (i.e. commands, queries, event subscribers, value objects...) from 
one context to another just as a skeleton for the new class.

So, what if this copy-paste work could be automated? With DDDMakerBundle, you'll be able to create DDD elements from your console, just like Symfony does through 
the [Maker Bundle](https://symfony.com/doc/current/bundles/SymfonyMakerBundle/index.html).

For now, these are the elements that will be able to be created through this bundle:

- **Bounded context**: ddd:make:bounded-context

- **CQRS**
    - Command (*ddd:cqrs:make:command*)
    - CommandHandler (*ddd:cqrs:make:command-handler*)
    - Query (*ddd:cqrs:make:query*)
    - QueryHandler (*ddd:cqrs:make:query-handler*)

- **Domain**
    - Event (*ddd:domain:make:event*)
    - Value Object (*ddd:domain:make:value-object*)
- **Application**
    - EventSubscriber (*ddd:application:make:event-subscriber)
    - UseCase (*ddd:application:make:use-case)

- **List**
    - Bounded (*contexs ddd:list:bounded-contexts*)
    - Commands (*ddd:cqrs:list:commands*)
    - CommandHandlers (*ddd:cqrs:list:command-handlers*)
    - Querys (*ddd:cqrs:list:queries*)
    - QueryHandler (*ddd:cqrs:list:query-handlers*)
    - Events (*ddd:domain:list:events*)
    - EventSubscribers (*ddd:application:list:event-subscribers*)
    - UseCases (*ddd:cqrs:list:use-cases*)
    
  ### Contributing
  
  As you might have noticed, there are a lot of console commands to implemen, so any help will be very welcome! If you want to contribute, just reach out opening an issue commenting the work 
  you'd like to do so maybe I can give you some help!
