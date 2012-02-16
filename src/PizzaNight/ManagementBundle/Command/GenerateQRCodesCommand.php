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

class GenerateQRCodesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('events:generate:qrcode')
            ->setDefinition(array(
                new InputArgument('event_id', InputArgument::REQUIRED, 'Event id, get it from command events:list'),
            ))
            ->setDescription('Generates the QRCode for the event attendees.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('QRCodes generated');
        $output->writeln('----------------------------------------');

        $attendees = $this->getContainer()->get('doctrine')->getRepository('PizzaNightManagementBundle:Attendee')->findBy(array('event_id' => $input->getArgument('event_id')));

        $qrcode_directory = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'qrcodes' . DIRECTORY_SEPARATOR;

        foreach ($attendees as $attendee) {
            if(!file_exists($qrcode_directory . $attendee->getSlug())) {
                \PHPQRCode_QRcode::png($attendee->getSlug(), $qrcode_directory . $attendee->getSlug(), 'L', 4, 2);
                $output->writeln($attendee->getContact()->getName() . ' <' . $attendee->getContact()->getEmail() . '>');
            }
        }

        $output->writeln("");
    }

}
