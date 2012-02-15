<?php
/**
 * EventsListCommand.php
 *
 * Created by arielferrandini
 */
namespace PizzaNight\ManagementBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class EventsListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('events:list')
            ->setDefinition(array(
                new InputArgument('past',
                InputArgument::OPTIONAL,
                'Show past events'
                )
            ))
            ->setDescription('Shows the events list.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('List of events');
        $output->writeln('----------------------------------------');

        $repository = $this->getContainer()->get('doctrine')->getRepository('PizzaNightManagementBundle:Event');

        if($input->getArgument('past')) {
            $events = $repository->findAll();
        } else {
            $events = $repository->findNextEvents();
        }

        foreach ($events as $event) {
            $output->writeln($event->getId() . "\t" . $event->getDate()->format('d/m/Y') . "\t" . $event->getName());
        }


        $output->writeln("");
    }

}
