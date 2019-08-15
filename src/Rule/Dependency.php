<?php declare(strict_types=1);

namespace PHPArchiTest\Rule;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use Roave\BetterReflection\Reflection\ReflectionClass;

class Dependency implements RuleType
{
    /** @var ClassLike */
    private $originAst;

    public function satisfies(ReflectionClass $origin, ReflectionClass $destination): bool
    {
        $this->originAst = $origin->getAst();

        $dependencies = $this->getDependencies();

        return in_array($destination->getName(), $dependencies);
    }

    public function getMessageVerb(): string
    {
        return 'depend on';
    }

    private function getDependencies(): array
    {
        $methods = $this->originAst->getMethods();

        $deps = [];
        foreach ($methods as $method) {
            $deps = array_merge(
                $deps,
                $this->extractMethodDeps($method),
                $this->extractMethodParamsDeps($method),
                $this->extractMethodReturnDeps($method)
                //$this->extractDocDeps($method)
            );
        }

        return array_unique($deps);
    }

    private function extractMethodDeps(ClassMethod $method): array
    {
        $dependencies = [];
        try {
            $stmts = $method->getStmts();
            if ($stmts) {
                foreach ($stmts as $stmt) {
                    $dependencies = array_merge(
                        $dependencies,
                        $this->extractNodeDeps($stmt)
                        //$this->extractFromExceptionsCatched($method)
                    );
                }
            }

            return $dependencies;
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $this->originAst->name.PHP_EOL;
            die;
        }
    }

    private function extractNodeDeps(\PhpParser\Node $node): array
    {
        $dependencies = [];
        foreach ($node as $subnode) {
            if ($subnode instanceof \PhpParser\Node\Name\FullyQualified) {
                $dependencies[] = implode('\\', $subnode->parts);
            }
            if ($subnode instanceof \PhpParser\Node) {
                $dependencies = array_merge($dependencies, $this->extractNodeDeps($subnode));
            } elseif (is_array($subnode)) {
                foreach ($subnode as $item) {
                    if (!is_scalar($item)) {
                        $dependencies = array_merge($dependencies, $this->extractNodeDeps($item));
                    }
                }
            }
        }

        return $dependencies;
    }

    private function extractMethodParamsDeps(ClassMethod $method): array
    {
        $dependencies = [];
        foreach ($method->getParams() as $param) {
            $type = $param->type;
            if (is_null($type) || !isset($type->parts)) {
                continue;
            }

            $dependencies[] = implode('\\', $type->parts);
        }

        return $dependencies;
    }

    private function extractMethodReturnDeps(ClassMethod $method): array
    {
        $dependencies = [];
        $rType = $method->getReturnType();
        if (!is_null($rType) && isset($rType->parts)) {
            $dependencies[] = implode('\\', $rType->parts);
        }

        return $dependencies;
    }

//    private function extractDocDeps(ClassMethod $method)
//    {
//        //getDocBlockTypes
//        //getDocBlockTypeStrings
//        //getDocComment
//        //getComments
//    }
}
