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

use PizzaNight\ManagementBundle\Entity\Event;

class ImportRegisteredPeopleCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('events:import:people')
            ->setDefinition(array(
                new InputArgument('event_id', InputArgument::REQUIRED, 'Event id, get it from command events:list'),
                new InputArgument('filename', InputArgument::REQUIRED, 'Excel file you want to import')
                )
            )
            ->setDescription('Imports the registered people from the excel.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $event_id = $input->getArgument('event_id');
        $filename = $input->getArgument('filename');

        // Check the event_id exists
        if(is_numeric($event_id) && $event_id>0) {
            $event = $this->getContainer()->get('doctrine')->getRepository('PizzaNightManagementBundle:Event')->find($event_id);
        } else {
            $event = null;
        }

        if($event instanceof Event) {
            if(file_exists($filename)) {
                $output->writeln('PizzaNighters registered:');
                $output->writeln('----------------------------------------');
            } else {
                throw new \InvalidArgumentException('Please introduce a valid Excel file');
            }
        } else {
            throw new \InvalidArgumentException('Please introduce a valid event_id');
        }

        $output->writeln("");
    }

}
