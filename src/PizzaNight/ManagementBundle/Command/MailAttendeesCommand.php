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

class MailAttendeesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('events:send:emails')
            ->setDefinition(array(
                new InputArgument('event_id', InputArgument::REQUIRED, 'Event id, get it from command events:list'),
            ))
            ->setDescription('Generates and spools the emails.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Emails generated');
        $output->writeln('----------------------------------------');

        $attendees = $this->getContainer()->get('doctrine')->getRepository('PizzaNightManagementBundle:Attendee')->findBy(array('event_id' => $input->getArgument('event_id')));

        foreach ($attendees as $attendee) {
            $output->writeln($attendee->getContact()->getName() . ' <' . $attendee->getContact()->getEmail() . '>');

            $message = \Swift_Message::newInstance()
                ->setSubject('Mail test')
                ->setFrom('arielferrandini@gmail.com')
                ->setTo('arielferrandini@gmail.com')
                ->setBody("Body"); //$this->renderView('HelloBundle:Hello:email.txt.twig', array('name' => $name)));

            $this->getContainer()->get('mailer')->send($message);

        }

        $output->writeln("");
    }

}
