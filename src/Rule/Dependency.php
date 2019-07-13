<?php declare(strict_types=1);

namespace PHPArchiTest\Rule;

use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use Roave\BetterReflection\Reflection\ReflectionClass;

class Dependency implements RuleType
{
    /** @var ClassLike */
    private $originAst;
    /** @var array */
    private $dependencies = [];

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

        foreach ($methods as $method) {
            $this->extractMethodDeps($method);
            $this->extractMethodParamsDeps($method);
            $this->extractMethodReturnDeps($method);
            //$this->extractDocDeps($method);
        }

        return array_unique($this->dependencies);
    }

    private function extractMethodDeps(ClassMethod $method)
    {
        try {
            $stmts = $method->getStmts();
            if ($stmts) {
                foreach ($stmts as $stmt) {
                    $this->extractNodeDeps($stmt);
                    //$this->extractFromExceptionsCatched($method);
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            echo $this->originAst->name.PHP_EOL;
            die;
        }
    }

    private function extractNodeDeps(\PhpParser\Node $node)
    {
        foreach ($node as $subnode) {
            if ($subnode instanceof \PhpParser\Node\Name\FullyQualified) {
                $this->dependencies[] = implode('\\', $subnode->parts);
            }
            if ($subnode instanceof \PhpParser\Node) {
                $this->extractNodeDeps($subnode);
            } elseif (is_array($subnode)) {
                foreach ($subnode as $item) {
                    if (!is_scalar($item)) {
                        $this->extractNodeDeps($item);
                    }
                }
            }
        }
    }
/*
 *
Warning: Invalid argument supplied for foreach() in /Users/carlosalandete/PhpstormProjects/php-at/src/Rule/Dependency.php on line 47
PHP Warning:  Invalid argument supplied for foreach() in /Users/carlosalandete/PhpstormProjects/php-at/src/Rule/Dependency.php on line 47

 */
    private function extractMethodParamsDeps(ClassMethod $method)
    {
        foreach ($method->getParams() as $param) {
            $type = $param->type;
            if (is_null($type) || !isset($type->parts)) {
                continue;
            }

            $this->dependencies[] = implode('\\', $type->parts);
        }
    }

    private function extractMethodReturnDeps(ClassMethod $method)
    {
        $rType = $method->getReturnType();
        if (!is_null($rType) && isset($rType->parts)) {
            $this->dependencies[] = implode('\\', $rType->parts);
        }
    }

//    private function extractDocDeps(ClassMethod $method)
//    {
//        //getDocBlockTypes
//        //getDocBlockTypeStrings
//        //getDocComment
//        //getComments
//    }
}
