<?php

namespace App\Factory;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Tags>
 *
 * @method        Tags|Proxy create(array|callable $attributes = [])
 * @method static Tags|Proxy createOne(array $attributes = [])
 * @method static Tags|Proxy find(object|array|mixed $criteria)
 * @method static Tags|Proxy findOrCreate(array $attributes)
 * @method static Tags|Proxy first(string $sortedField = 'id')
 * @method static Tags|Proxy last(string $sortedField = 'id')
 * @method static Tags|Proxy random(array $attributes = [])
 * @method static Tags|Proxy randomOrCreate(array $attributes = [])
 * @method static TagsRepository|RepositoryProxy repository()
 * @method static Tags[]|Proxy[] all()
 * @method static Tags[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Tags[]|Proxy[] createSequence(iterable|callable $sequence)
 * @method static Tags[]|Proxy[] findBy(array $attributes)
 * @method static Tags[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Tags[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TagsFactory extends ModelFactory
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
            // ->afterInstantiate(function(Tags $tags): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Tags::class;
    }
}
