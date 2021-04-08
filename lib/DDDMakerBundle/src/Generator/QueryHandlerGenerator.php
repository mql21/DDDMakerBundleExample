<?php

namespace Mql21\DDDMakerBundle\Generator;

use Mql21\DDDMakerBundle\Exception\ElementAlreadyExistsException;
use Mql21\DDDMakerBundle\Factories\PathFactory;
use Mql21\DDDMakerBundle\Generator\Contract\DDDElementGenerator;
use Mql21\DDDMakerBundle\Response\UseCaseResponse;
use Mql21\DDDMakerBundle\Templates\HandlerTemplateData;

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
        $modulePath = PathFactory::forBoundedContextModules($boundedContextName, $moduleName);
        $queryHandlerFullPath = "{$modulePath}/Application/Query/{$queryHandlerFileName}";
        
        if (file_exists($queryHandlerFullPath)) {
            throw new ElementAlreadyExistsException(
                "Query handler {$queryHandlerClassName} already exists in module \"{$moduleName}\" of bounded context \"{$boundedContextName}\"."
            );
        }
        
        file_put_contents(
            $queryHandlerFullPath,
            $this->renderQueryHandlerTemplate(
                new HandlerTemplateData(
                    "Mql21\DDDMakerBundle\Generator",
                    $queryHandlerClassName,
                    "App\Shared\Domain\Bus\Query\QueryHandler",
                    "{$queryName}Query",
                    $this->useCaseResponse->useCase(),
                    $this->responseClassName
                )
            )
        );
    }
    
    // TODO extract and inject as collaborator
    protected function renderQueryHandlerTemplate(HandlerTemplateData $templateData): string
    {
        $template = file_get_contents(
            "lib/DDDMakerBundle/src/Templates/query_handler.php.template"
        ); //TODO: get via config (DI)
        
        $interfaceReflectionClass = new \ReflectionClass($templateData->interfaceNamespace());
        
        $classContent = str_replace("{{t_namespace}}", $templateData->classNamespace(), $template);
        $classContent = str_replace(
            "{{t_interface_full_namespace}}",
            $templateData->interfaceNamespace(),
            $classContent
        );
        $classContent = str_replace("{{t_interface_name}}", $interfaceReflectionClass->getShortName(), $classContent);
        $classContent = str_replace("{{t_class_name}}", $templateData->className(), $classContent);
        $classContent = str_replace("{{t_use_case_class_name}}", $templateData->useCaseName(), $classContent);
        $classContent = str_replace("{{t_response_class_name}}", $templateData->response(), $classContent);
        $classContent = str_replace("{{t_command_class_name}}", $templateData->command(), $classContent);
        
        return $classContent;
    }
}