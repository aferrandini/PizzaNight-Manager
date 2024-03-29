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
use PizzaNight\ManagementBundle\Entity\Attendee;

class MailAttendeesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('events:send:emails')
            ->setDefinition(array(
                new InputArgument('event_id', InputArgument::REQUIRED, 'Event id, get it from command events:list'),
                new InputOption('send-all', null, InputArgument::OPTIONAL),
                new InputOption('send-accepted', null, InputArgument::OPTIONAL),
                new InputOption('send-rejected', null, InputArgument::OPTIONAL),
                new InputOption('send-slug', null, InputArgument::OPTIONAL)
            ))
            ->setDescription('Generates and spools the emails.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $send_all      = $input->getOption('send-all') ? true : false;
        $send_accepted = $input->getOption('send-accepted') ? true : false;
        $send_rejected = $input->getOption('send-rejected') ? true : false;
        $send_slug     = $input->getOption('send-slug') ? true : false;

        if($send_all || $send_accepted || $send_slug) {
            $output->writeln('Accepted attendees');
            $output->writeln('----------------------------------------');

            $findBy = array(
                'event_id' => $input->getArgument('event_id'),
                'status' => Attendee::STATUS_ACCEPTED
            );

            if($send_slug) {
                $findBy['slug'] = $input->getOption('send-slug');
            }

            $attendees = $this->getContainer()->get('doctrine')
                ->getRepository('PizzaNightManagementBundle:Attendee')
                ->findBy($findBy);

            if(count($attendees)>0) {
                $host = 'http://manager.pizzanight.neosistec.com';
                $mailer = $this->getContainer()->get('mailer');
                $twig = $this->getContainer()->get('twig');
                $top_image_path = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'MailTemplateImages' . DIRECTORY_SEPARATOR . 'top.gif';
                $bottom_image_path = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'MailTemplateImages' . DIRECTORY_SEPARATOR . 'bottom.gif';

                foreach ($attendees as $attendee) {
                    $output->writeln($attendee->getContact()->getName() . ' <' . $attendee->getContact()->getEmail() . '>');

                    $qrcode_path = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'qrcodes' . DIRECTORY_SEPARATOR . $attendee->getSlug();
                    $message = \Swift_Message::newInstance();
                    $message->setSubject('Ya eres un Pizza Nighter!')
                        ->setFrom('pizzanight@neosistec.com')
                        ->setTo($attendee->getContact()->getEmail())
                        ->setBcc("aferrandini.neosistec@gmail.com")
                        ->setBody($twig->render('PizzaNightManagementBundle:MailTemplates:Accepted/confirmation.html.twig', array(
                            'attendee' => $attendee,
                            'host' => $host,
                            'top_image' => $message->embed(\Swift_Image::fromPath($top_image_path)),
                            'bottom_image' => $message->embed(\Swift_Image::fromPath($bottom_image_path)),
                            'qrcode' => $message->embed(\Swift_Image::fromPath($qrcode_path)),
                        )), "text/html")
                        ->addPart($twig->render('PizzaNightManagementBundle:MailTemplates:Accepted/confirmation.txt.twig', array(
                            'attendee' => $attendee,
                            'host' => $host,
                        )), "text/plain")
                        ->attach(\Swift_Attachment::fromPath($qrcode_path)->setFilename('entrada.png'))
                    ;

                    $mailer->send($message);
                }
            } else {
                $output->writeln('No se encontraron asistentes al evento.');
            }
        }

        if($send_all || $send_rejected || $send_slug) {
            $output->writeln('');
            $output->writeln('Rejected attendees');
            $output->writeln('----------------------------------------');

            $findBy = array(
                'event_id' => $input->getArgument('event_id'),
                'status' => Attendee::STATUS_REJECTED
            );

            if($send_slug) {
                $findBy['slug'] = $input->getOption('send-slug');
            }

            $attendees = $this->getContainer()->get('doctrine')
                ->getRepository('PizzaNightManagementBundle:Attendee')
                ->findBy($findBy);

            if(count($attendees)>0) {
                $host = 'http://manager.pizzanight.neosistec.com';
                $mailer = $this->getContainer()->get('mailer');
                $twig = $this->getContainer()->get('twig');
                $top_image_path = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'MailTemplateImages' . DIRECTORY_SEPARATOR . 'top.gif';
                $bottom_image_path = $this->getContainer()->get('kernel')->getRootDir() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'MailTemplateImages' . DIRECTORY_SEPARATOR . 'bottom.gif';

                foreach ($attendees as $attendee) {
                    $output->writeln($attendee->getContact()->getName() . ' <' . $attendee->getContact()->getEmail() . '>');

                    $message = \Swift_Message::newInstance();
                    $message->setSubject('PizzaNight 22-Febrero!')
                        ->setFrom('pizzanight@neosistec.com')
                        ->setTo($attendee->getContact()->getEmail())
                        ->setBcc("aferrandini.neosistec@gmail.com")
                        ->setBody($twig->render('PizzaNightManagementBundle:MailTemplates:Rejected/sorry.html.twig', array(
                        'attendee' => $attendee,
                        'host' => $host,
                        'top_image' => $message->embed(\Swift_Image::fromPath($top_image_path)),
                        'bottom_image' => $message->embed(\Swift_Image::fromPath($bottom_image_path)),
                    )), "text/html")
                        ->addPart($twig->render('PizzaNightManagementBundle:MailTemplates:Rejected/sorry.txt.twig', array(
                        'attendee' => $attendee,
                        'host' => $host,
                    )), "text/plain")
                    ;

                    $mailer->send($message);
                }
            } else {
                $output->writeln('No se encontraron asistentes al evento.');
            }
        }

        $output->writeln("");
    }

}
