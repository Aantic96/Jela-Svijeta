<?php

namespace App\Factory;

use App\Entity\Ingredients;
use App\Repository\IngredientsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Ingredients>
 *
 * @method        Ingredients|Proxy create(array|callable $attributes = [])
 * @method static Ingredients|Proxy createOne(array $attributes = [])
 * @method static Ingredients|Proxy find(object|array|mixed $criteria)
 * @method static Ingredients|Proxy findOrCreate(array $attributes)
 * @method static Ingredients|Proxy first(string $sortedField = 'id')
 * @method static Ingredients|Proxy last(string $sortedField = 'id')
 * @method static Ingredients|Proxy random(array $attributes = [])
 * @method static Ingredients|Proxy randomOrCreate(array $attributes = [])
 * @method static IngredientsRepository|RepositoryProxy repository()
 * @method static Ingredients[]|Proxy[] all()
 * @method static Ingredients[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Ingredients[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Ingredients[]|Proxy[] findBy(array $attributes)
 * @method static Ingredients[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Ingredients[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class IngredientsFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'createdAt' => \DateTimeImmutable::createFromMutable(self::faker()->dateTime()),
            'slug' => self::faker()->word,
            'title' => self::faker()->word
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Ingredients $ingredients): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Ingredients::class;
    }
}
