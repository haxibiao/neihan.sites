<?php

namespace App\GraphQL\Directives;

use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Exceptions\DefinitionException;
use Nuwave\Lighthouse\Schema\Directives\BaseDirective;
use Nuwave\Lighthouse\Schema\Values\FieldValue;
use Nuwave\Lighthouse\Support\Contracts\FieldResolver;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;
use Nuwave\Lighthouse\Support\Utils;
use ReflectionFunction;

class ResolverDirective extends BaseDirective implements FieldResolver
{

    public static function definition(): string
    {
        return /** @lang GraphQL */<<<'SDL'
directive @resolver(
  name: String!
) on FIELD_DEFINITION
SDL;

    }

    public function name(): string
    {
        return /** @lang GraphQL */<<<'SDL'
directive @resolver(
  name: String!
) on FIELD_DEFINITION
SDL;
    }

    /**
     * @param FieldValue $fieldValue
     *
     * @return FieldValue
     * @throws DefinitionException
     */
    public function resolveField(FieldValue $fieldValue)
    {
        [$className, $methodName] = $this->getMethodArgumentParts('name');
        $namespacedClassName      = $this->namespaceClassName(
            $className,
            $fieldValue->defaultNamespacesForParent()
        );
        $resolver = Utils::constructResolver($namespacedClassName, $methodName);
        return $fieldValue->setResolver(
            static function ($root, array $args, GraphQLContext $context, ResolveInfo $resolveInfo) use ($resolver) {
                $newArgs = [];
                foreach ((new ReflectionFunction($resolver))->getParameters() as $param) {
                    if (array_key_exists($param->name, $args)) {
                        $newArgs[] = $args[$param->name];
                        continue;
                    }
                    switch ($param->name) {
                        case 'root':
                            $newArgs[] = $root;
                            break;
                        case 'context':
                            $newArgs[] = $context;
                            break;
                        case 'resolveInfo':
                            $newArgs[] = $resolveInfo;
                            break;
                        default:
                            $newArgs[] = null;
                    }
                }
                return $resolver(...$newArgs);
            }
        );
    }
}
