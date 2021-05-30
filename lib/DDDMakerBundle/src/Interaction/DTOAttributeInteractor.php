<?php

namespace Mql21\DDDMakerBundle\Interaction;

use Mql21\DDDMakerBundle\ValueObject\ClassAttributes;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class DTOAttributeInteractor
{
    public function ask(InputInterface $input, OutputInterface $output, $questionHelper): ClassAttributes
    {
        $output->writeln("<info>\n Now tell me some info about its attributes! </info>\n\n");
        
        $availableTypes = ["string", "int", "float", "bool", "array"]; // TODO: abstract this
        $attributes = [];
        $continueAskingAttributes = true;
        while ($continueAskingAttributes) {
            $attributeNameQuestion = new Question("<info> What should the attribute be called?</info>\n > ");
            $attributeName = $questionHelper->ask($input, $output, $attributeNameQuestion);
            $attributeTypeQuestion = new ChoiceQuestion(
                "<info> What type should the attribute be?</info>\n > ",
                $availableTypes
            );
            $attributeTypeQuestion->setAutocompleterValues($availableTypes);
            $attributeType = $questionHelper->ask($input, $output, $attributeTypeQuestion);
            
            $attributes[$attributeName] = $attributeType;
            
            $createQueryHandlerQuestion = new ConfirmationQuestion(
                "<info> Do you wish to add another attribute (y/n)?</info>\n > ",
                false,
                '/^(y|s)/i'
            );
            
            $continueAskingAttributes = $questionHelper->ask($input, $output, $createQueryHandlerQuestion);
        }
        
        return new ClassAttributes($attributes);
    }
}