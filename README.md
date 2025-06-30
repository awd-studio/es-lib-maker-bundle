# ES Lib Maker Bundle

A Symfony bundle providing maker commands for seamless integration with Event Sourcing patterns. Features code generation tools for event-sourced aggregates, value objects, and domain events following Domain-Driven Design principles.

[![MIT License](https://img.shields.io/badge/License-MIT-green.svg)](https://choosealicense.com/licenses/mit/)
[![PHP Version](https://img.shields.io/badge/PHP-8.3%2B-blue)](https://www.php.net/)
[![Symfony Version](https://img.shields.io/badge/Symfony-7.0%2B-black)](https://symfony.com/)

## Overview

ES Lib Maker Bundle is part of the ES Lib ecosystem, designed to simplify the implementation of Event Sourcing in Symfony applications. This bundle provides maker commands that generate boilerplate code for event-sourced entities, following best practices and patterns.

## Installation

### Prerequisites

- PHP 8.3 or higher
- Symfony 7.0 or higher
- Composer

### Steps

1. Require the bundle via Composer as a dev dependency:

```bash
composer require --dev awd-studio/es-lib-maker-bundle
```

The bundle will be automatically registered in your `config/bundles.php` for the dev environment.

If the bundle is not registered automatically, you can register it manually by adding the following line to your `config/bundles.php` file:

```php
return [
    // ...
    AwdEs\EsLibMakerBundle\EsLibMakerBundle::class => ['dev' => true],
];
```

## Usage

### Creating an Event-Sourced Entity

The bundle provides a maker command to generate event-sourced entities:

```bash
# Create an aggregate root
php bin/console make:es:entity "YourNamespace\YourAggregate" -

# Create a child entity
php bin/console make:es:entity "YourNamespace\YourAggregate\ChildEntity" "App\YourNamespace\Domain\YourAggregate"
```

#### Command Options

- `entity-name`: The fully qualified class name for the entity (e.g., `Foo\FooAggregate`)
- `aggregate-root`: The root for the aggregate (use `-` to configure the aggregate root itself)
- `--machine-name`: A unique name for the entity (e.g., `FOO_AGGREGATE`)
- `--main-value-type`: The type for the main value (default: `string`)
- `--main-value-name`: The name for the main value (default: `value`)

### Generated Code Structure

For each entity, the bundle generates:

1. **Entity Class**: The main entity class with event handlers
2. **Events**:
   - `WasCreated`: Event for entity creation
   - `WasChanged`: Event for entity changes
   - `WasActivated`/`WasDeactivated`: Events for entity activation/deactivation (for simple entities)
3. **Repository**:
   - Interface
   - Implementation
4. **Factory**:
   - Interface
   - Implementation
5. **Exceptions**:
   - `NotFound`: Exception for when an entity is not found
   - `PersistenceError`: Exception for persistence errors

## Examples

### Creating an Aggregate Root

#### Basic String-Based Aggregate

```bash
php bin/console make:es:entity "Product\ProductAggregate" - --main-value-type="string" --main-value-name="name"
```

This will generate:
- `App\Product\Domain\ProductAggregate` - The aggregate root class
- Events, repository, factory, and exceptions for the aggregate

#### Integer-Based Aggregate with Custom Machine Name

```bash
php bin/console make:es:entity "Order\OrderAggregate" - --main-value-type="int" --main-value-name="orderNumber" --machine-name="CUSTOMER_ORDER"
```

This will generate:
- `App\Order\Domain\OrderAggregate` - The aggregate root class with an integer main value
- Events, repository, factory, and exceptions for the aggregate
- The machine name "CUSTOMER_ORDER_ROOT" will be used in the entity attributes

#### DateTime-Based Aggregate

```bash
php bin/console make:es:entity "Appointment\AppointmentAggregate" - --main-value-type="IDateTime" --main-value-name="scheduledAt"
```

This will generate:
- `App\Appointment\Domain\AppointmentAggregate` - The aggregate root class with a DateTime main value
- Special comparison logic for DateTime objects in the change method

### Creating Child Entities

#### Basic String-Based Child Entity

```bash
php bin/console make:es:entity "Product\ProductAggregate\ProductVariant" "App\Product\Domain\ProductAggregate" --main-value-type="string" --main-value-name="sku"
```

This will generate:
- `App\Product\Domain\Entity\ProductVariant\ProductVariant` - The child entity class
- Events, repository, factory, and exceptions for the child entity

#### Boolean-Based Child Entity (Simple Entity)

```bash
php bin/console make:es:entity "User\UserAggregate\UserPreference" "App\User\Domain\UserAggregate" --main-value-type="bool" --main-value-name="isEnabled"
```

This will generate:
- `App\User\Domain\Entity\UserPreference\UserPreference` - A simple child entity with boolean state
- Activation/deactivation events and methods

#### Nested Child Entity with Custom Machine Name

```bash
php bin/console make:es:entity "Order\OrderAggregate\OrderItem\ItemDiscount" "App\Order\Domain\OrderAggregate" --main-value-type="float" --main-value-name="amount" --machine-name="ORDER_ITEM_DISCOUNT"
```

This will generate:
- `App\Order\Domain\Entity\OrderItem\ItemDiscount\ItemDiscount` - A nested child entity
- The machine name "ORDER_ITEM_DISCOUNT" will be used in the entity attributes

## Related Projects

The ES Lib ecosystem includes the following projects:

- [ES Lib](https://github.com/awd-studio/es-lib): Core library for Event Sourcing
- [ES Lib Bundle](https://github.com/awd-studio/es-lib-bundle): Symfony bundle for the ES Lib library
- [ES Lib Maker Bundle](https://github.com/awd-studio/es-lib-maker-bundle): This project, providing maker commands for Event Sourcing entities

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the MIT License - see the LICENSE file for details.
