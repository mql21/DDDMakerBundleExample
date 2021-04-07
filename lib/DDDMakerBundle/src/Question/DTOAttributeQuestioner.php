<?php
namespace Mql21\DDDMakerBundle\Question;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class DTOAttributeQuestioner
{
    public function ask(InputInterface $input, OutputInterface $output, $questionHelper): array
    {
        $attributes = [];
        $continueAskingAttributes = true;
        while($continueAskingAttributes) {
            $attributeNameQuestion = new Question("<info> What should the attribute be called?</info>\n > ");
            $attributeName = $questionHelper->ask($input, $output, $attributeNameQuestion);
    
            $attributeTypeQuestion = new Question("<info> What type should the attribute be?</info>\n > ");
            $attributeType = $questionHelper->ask($input, $output, $attributeTypeQuestion);
            
            $attributes[$attributeName] = $attributeType;
                
            $createQueryHandlerQuestion = new ConfirmationQuestion(
                "<info> Do you wish to add another attribute (y/n)?</info>\n > ",
                false,
                '/^(y|s)/i'
            );
    
            $continueAskingAttributes = $questionHelper->ask($input, $output, $createQueryHandlerQuestion);
        }
        
        return $attributes;
    }
}