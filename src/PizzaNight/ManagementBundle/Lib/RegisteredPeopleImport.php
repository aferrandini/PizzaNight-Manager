<?php
/**
 * RegisteredPeopleImport.php
 *
 * Created by arielferrandini
 */
namespace PizzaNight\ManagementBundle\Lib;

use Symfony\Component\DependencyInjection\Container;
use PizzaNight\ManagementBundle\Entity\Event;
use PizzaNight\ManagementBundle\Entity\Contact;
use PizzaNight\ManagementBundle\Entity\Attendee;

class RegisteredPeopleImport
{
    protected $doctrine = null;

    public function __construct($doctrine)
    {
        $this->setDoctrine($doctrine);
    }

    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $filename
     * @param int $event_id
     * @param int $start_at
     * @throws \InvalidArgumentException
     */
    public function importFile(\Symfony\Component\Console\Output\OutputInterface $output, $filename, $event_id, $dialog=null, $start_at=2)
    {
        $event = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Event')->find($event_id);

        if(!($event instanceof \PizzaNight\ManagementBundle\Entity\Event)) {
            throw new \InvalidArgumentException("Invalid event");
        }

        if(!file_exists($filename)) {
            throw new \InvalidArgumentException("The filename must be an existant file");
        }

        try {
            $objReader = \PHPExcel_IOFactory::createReaderForFile($filename);
            $objReader->setReadDataOnly(true);
            $objPHPExcel = $objReader->load($filename);
        } catch (Exception $e) { // An error has ocurred
            throw $e;
        }

        $output->writeln('Event: ' . $event->getName());
        $output->writeln('PizzaNighters registered:');
        $output->writeln('----------------------------------------');

        // Get the active sheet
        $activeSheet = $objPHPExcel->getActiveSheet();

        $em = $this->getDoctrine()->getEntityManager();
        $contactRepository     = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Contact');
        $attendeeRepository    = $this->getDoctrine()->getRepository('PizzaNightManagementBundle:Attendee');

        /**
         * A:   Marca tempora
         * B:   Nombre completo
         * D:   Email
         * E:   Teléfono de contacto
         * F:	A qué te dedicas?
         * G:	Cuéntanos un poco sobre tí
         * H:	Que sistema operativo móvil prefieres?
         * I:	Cuál es tu pizza favorita?
         * J:	Por qué quieres venir al Pizza Night 2012?
         * K:	Quieres presentar una aplicación o una idea?
         * L:	Nombre de la aplicación
         * M:	Descripción de la aplicación
         */
        $attendees = array();
        $registered = 0;
        $row = $start_at;
        while($this->getCellValue($activeSheet, 'A'.$row)!='') {
            $contact = $contactRepository->findOneBy(array('email' => $this->getCellValue($activeSheet, 'D'.$row)));
            if(!$contact) {
                $contact = new Contact();
                $contact->setCreatedAt(new \DateTime());
                $contact->setEmail($this->getCellValue($activeSheet, 'D'.$row));
                $contact->setAboutMe($this->getCellValue($activeSheet, 'G'.$row));
            }

            $contact->setName($this->getCellValue($activeSheet, 'B'.$row));
            $contact->setPhone($this->getCellValue($activeSheet, 'E'.$row));
            $contact->setType($this->getCellValue($activeSheet, 'F'.$row));
            $em->persist($contact);
            $em->flush();

            $attendee = $attendeeRepository->findOneBy(array('event_id' => $event->getId(), 'contact_id' => $contact->getId()));
            if(!($attendee instanceof Attendee)) {
                $attendee = new Attendee();
                $attendee->setEvent($event);
                $attendee->setContact($contact);
                $attendee->setDate(new \DateTime());
                $attendee->setStatus(Attendee::STATUS_REGISTERED);
                $attendee->setWhy($this->getCellValue($activeSheet, 'J'.$row));
                $attendee->setPizza($this->getCellValue($activeSheet, 'I'.$row));
                $attendee->setSlug('slug');

                $attendees[] = $attendee;

                $output->writeln($contact->getName() . ' <' . $contact->getEmail() . '>');

                $registered++;
            }

            $row++;
        }

        if($registered>0) {
            if(!is_null($dialog)) {
                $output->writeln("");

                $respuesta = $dialog->askAndValidate(
                    $output,
                    '<question>¿Quieres registrar los contactos anteriores en el evento? [S/N]</question> ',
                    function($valor) {
                        $valor = strtoupper($valor);
                        if(!in_array($valor, array('S','N'))) {
                            throw new \InvalidArgumentException('Debes responder [S/N]');
                        }

                        return ($valor=='S'?true:false);
                    },
                    'S'
                );
            } else {
                $respuesta = true;
            }

            if($respuesta==true) {
                $output->writeln('Registrando personas en el evento...');
                foreach($attendees as $attendee) {
                    $em->persist($attendee);
                }

                $em->flush();
            }
        }
    }

    /**
     *
     * Process to convert Excel:DATE_TIME to TIMESTAMP
     * @param EXCEL_DATETIME $dateValue
     */
    private function convertDate($dateValue)
    {
        $dateValue = intval($dateValue);

        $time = mktime(
        PHPExcel_Calculation_DateTime::HOUROFDAY($dateValue),
        PHPExcel_Calculation_DateTime::MINUTEOFHOUR($dateValue),
        PHPExcel_Calculation_DateTime::SECONDOFMINUTE($dateValue),
        PHPExcel_Calculation_DateTime::MONTHOFYEAR($dateValue),
        PHPExcel_Calculation_DateTime::DAYOFMONTH($dateValue),
        PHPExcel_Calculation_DateTime::YEAR($dateValue)
        );

        return $time;
    }

    /**
     *
     * Returns a cell value from an activeSheet and a coordinate
     * @param PHPExcel_Worksheet $activeSheet
     * @param String $coordinate
     */
    private function getCellValue(\PHPExcel_Worksheet $activeSheet, $coordinate)
    {
        if($activeSheet instanceof \PHPExcel_Worksheet && $coordinate!='') {
            $cell = $activeSheet->getCell($coordinate);

            if($cell!=null) {
                return trim($cell->getValue());
            }
        }

        return null;
    }

}
