<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Generator\Builder\DDDClassBuilder;
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
        $dddClassBuilder = DDDClassBuilder::create()
            ->forBoundedContext($boundedContextName)
            ->forModule($moduleName)
            ->withClassName($handlerName)
            ->ofDDDElementType($this->type())
            ->build();
    
        if (file_exists($dddClassBuilder->elementFullPath())) {
            throw new ElementAlreadyExistsException(
                "Query handler {$dddClassBuilder->elementClassName()} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        $useCaseNamespace = $this
            ->configManager->namespaceFor($boundedContextName, $moduleName, "use-case");
        $responseNamespace = $this->
            configManager->namespaceFor($boundedContextName, $moduleName, "response");
        $querySuffix = $this->configManager->classSuffixFor('query');
        $responseSuffix = $this->configManager->classSuffixFor('response');
        $responseClassName = "{$this->responseClassName}{$responseSuffix}";
        file_put_contents(
            $dddClassBuilder->elementFullPath(),
            $this->renderer->render(
                "lib/DDDMakerBundle/src/Templates/query_handler.php.template",
                [
                    "t_namespace" => $dddClassBuilder->namespace(),
                    "t_class_name" => $dddClassBuilder->elementClassName(),
                    "t_interface_full_namespace" => $dddClassBuilder->interfaceToImplementNamespace(),
                    "t_use_case_namespace" => $useCaseNamespace . "\\" . $this->useCaseResponse->useCase(),
                    "t_response_namespace" => $responseNamespace . "\\" . $responseClassName,
                    "t_interface_name" => $dddClassBuilder->interfaceToImplementName(),
                    "t_use_case_class_name" => $this->useCaseResponse->useCase(),
                    "t_response_class_name" => $responseClassName,
                    "t_query_class_name" => "{$handlerName}{$querySuffix}",
                ]
            )
        );
    }
    
    public function type(): string
    {
        return 'query-handler';
    }
}