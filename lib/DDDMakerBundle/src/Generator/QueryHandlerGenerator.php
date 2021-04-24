<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\ConfigManager\ConfigManager;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Renderer\PHPCodeRenderer;
use Mql21\DDDMakerBundle\Response\UseCaseResponse;

class QueryHandlerGenerator extends HandlerGenerator implements DDDElementGenerator
{
    private string $responseClassName;
    
    public function __construct(UseCaseResponse $useCaseResponse, string $responseClassName)
    {
        parent::__construct($useCaseResponse);
        $this->responseClassName = $responseClassName;
    }
    
    public function generate(string $boundedContextName, string $moduleName, string $queryName): void
    {
        $querySuffix = "QueryHandler";
        $queryHandlerClassName = "{$queryName}{$querySuffix}";
        $queryHandlerFileName = "{$queryHandlerClassName}.php";
        $queriesPath = PathFactory::forQueriesIn($boundedContextName, $moduleName);
        $queryHandlerFullPath = "{$queriesPath}{$queryHandlerFileName}";
        
        if (file_exists($queryHandlerFullPath)) {
            throw new ElementAlreadyExistsException(
                "Query handler {$queryHandlerClassName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        $baseClassReflector = new \ReflectionClass(
            $this->configManager->getClassToImplementFor('query-handler')
        );
        
        $renderer = new PHPCodeRenderer();
        file_put_contents(
            $queryHandlerFullPath,
            $renderer->render(
                "lib/DDDMakerBundle/src/Templates/query_handler.php.template",
                [
                    "t_namespace" => "Mql21\DDDMakerBundle\Generator",
                    "t_class_name" => $queryHandlerClassName,
                    "t_interface_full_namespace" => $baseClassReflector->getName(),
                    "t_interface_name" => $baseClassReflector->getShortName(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_response_class_name" => $this->responseClassName,
                    "t_query_class_name" => "{$queryName}Query",
                ]
            )
        );
    }
}