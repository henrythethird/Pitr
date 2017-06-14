<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TryApiCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:try_api')
            ->setDescription('Hello PhpStorm');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        echo ($this->getContainer()->get('ocr')
            ->extractText("http://data0.kif.fr/oz-trip/perso/payement_lang.jpg"));
    }
}
