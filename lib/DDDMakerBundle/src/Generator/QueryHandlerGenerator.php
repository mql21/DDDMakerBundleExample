<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\DTO\ClassDTO;
use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Response\UseCaseResponse;

class QueryHandlerGenerator extends HandlerGenerator implements DDDElementGenerator
{
    private string $responseClassName;
    
    public function __construct(UseCaseResponse $useCaseResponse, string $responseClassName)
    {
        parent::__construct($useCaseResponse);
        $this->responseClassName = $responseClassName;
    }
    
    public function generate(string $boundedContextName, string $moduleName, string $handlerName): void
    {
        $classDTO = new ClassDTO($boundedContextName, $moduleName, $handlerName, 'query-handler');
    
        if (file_exists($classDTO->elementFullPath())) {
            throw new ElementAlreadyExistsException(
                "Query handler {$classDTO->elementClassName()} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $useCaseNamespace = $this
            ->configManager->getNamespaceFor($boundedContextName, $moduleName, "use-case");
        $responseNamespace = $this->
            configManager->getNamespaceFor($boundedContextName, $moduleName, "response");
        $querySuffix = $this->configManager->getClassSuffixFor('query');
    
        file_put_contents(
            $classDTO->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/query_handler.php.template",
                [
                    "t_namespace" => $classDTO->namespace(),
                    "t_class_name" => $classDTO->elementClassName(),
                    "t_interface_full_namespace" => $classDTO->interfaceToImplementNamespace(),
                    "t_use_case_namespace" => $useCaseNamespace . "\\" . $this->useCaseResponse->useCase(),
                    "t_response_namespace" => $responseNamespace . "\\" . $this->responseClassName,
                    "t_interface_name" => $classDTO->interfaceToImplementName(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_response_class_name" => $this->responseClassName,
                    "t_query_class_name" => "{$handlerName}{$querySuffix}",
                ]
            )
        );
    }
}