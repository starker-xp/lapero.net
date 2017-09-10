<?php

namespace Starkerxp\StructureBundle\Command;


use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractImportCommand extends LockCommand
{
    protected $type;
    protected $origine;

    protected $modeSelection;
    protected $force = false;
    protected $nombreElementsRestant;
    protected $nombreElementsParPaquet = 50;
    protected $nombreDeMinutes = 5;
    protected $dateMin;
    protected $dateMax;
    protected $nombreElementsDejaEnvoye = 0;
    protected $nombreDeJourATraiter = 0;

    public function lockerName()
    {
        $modeSelection = $this->input->getOption("mode");
        $nomLocker = !in_array($modeSelection, ['trigger', 'date']) ? "" : $modeSelection;

        return $this->getName().(!empty($nomLocker) ? ":".$nomLocker : "");
    }

    public function traitement()
    {
        // On commence par déterminer le mode sélection des données.
        $modeSelection = $this->input->getOption("mode");
        $this->modeSelection = !in_array($modeSelection, ['trigger', 'date']) ? "" : $modeSelection;
        $this->force = $this->input->getOption("force");
        $this->nombreElementsRestant = $this->input->getOption("nombreElements");
        $this->nombreElementsParPaquet = $this->input->getOption("nombreParPaquet");
        // Gestion de la configuration pour le mode trigger
        if ($this->modeSelection == "trigger" && (int)$this->input->getOption("nombreDeMinutes")) {
            $nombreDeMinutes = (int)$this->input->getOption("nombreDeMinutes");
            $dateMin = (new \DateTime())->sub(new \DateInterval("PT".$nombreDeMinutes."M"));
            $dateMax = new \DateTime();
            $this->dateMin = $dateMin->format("Y-m-d H:i:s");
            $this->dateMax = $dateMax->format("Y-m-d H:i:s");
        }
        if ($this->modeSelection == "date") {
            $jourMinimum = \DateTime::createFromFormat('Y-m-d', $this->input->getOption("jourMinimum"));
            $jourMaximum = \DateTime::createFromFormat('Y-m-d', $this->input->getOption("jourMaximum"));
            // On s'assure que les dates soit dans le bon sens.
            if (!empty($jourMinimum) && !empty($jourMaximum) && $jourMaximum->format('Y-m-d') < $jourMinimum->format('Y-m-d')) {
                $tmp = $jourMaximum;
                $jourMaximum = $jourMinimum;
                $jourMinimum = $tmp;
                unset($tmp);
            }
            $this->nombreDeJourATraiter = abs((int)$this->input->getOption("nombreDeJours"));
            if (empty($jourMinimum) && empty($jourMaximum)) {
                // min = j-1-$this->nombreDeJourATraiter max =j-1
                $jourMinimum = (new \DateTime())->sub(new \DateInterval("P".($this->nombreDeJourATraiter + 1)."D"));
                $jourMaximum = (new \DateTime())->sub(new \DateInterval("P1D"));
            } else {
                if (!empty($jourMinimum) && empty($jourMaximum)) {
                    //  max = jMin+$this->nombreDeJourATraiter
                    $jourMaximum = (new \DateTime($jourMinimum->format("Y-m-d")))->add(new \DateInterval("P".$this->nombreDeJourATraiter."D"));
                } else {
                    if (empty($jourMinimum) && !empty($jourMaximum)) {
                        // min = jMax-$this->nombreDeJourATraiter
                        $jourMinimum = (new \DateTime($jourMaximum->format("Y-m-d")))->sub(new \DateInterval("P".($this->nombreDeJourATraiter + 1)."D"));
                    }
                }
            }
            $this->dateMin = $jourMinimum->format("Y-m-d 00:00:00");
            $this->dateMax = $jourMaximum->format("Y-m-d 00:00:00");
        }
        $this->output->writeln(
            "<info>[INFO]</info> Le script va exporter les données par paquet de <info>".$this->nombreElementsParPaquet."</info> éléments.",
            OutputInterface::VERBOSITY_VERBOSE
        );
        if (!empty($this->nombreElementsRestant)) {
            $this->output->writeln(
                "<info>[INFO]</info> Le script s'arrêtera après avoir exporté <info>".$this->nombreElementsRestant."</info> éléments.",
                OutputInterface::VERBOSITY_VERBOSE
            );
        }
        if (in_array($this->modeSelection, ["date", "trigger"])) {
            $this->output->writeln(
                "<info>[INFO]</info> Traitement des données entre le <info>".$this->dateMin."</info> et le <info>".$this->dateMax."</info>.",
                OutputInterface::VERBOSITY_VERBOSE
            );
        }
        if ($this->input->getOption("force")) {
            $this->output->writeln("<info>[INFO]</info> Le script renverra <info>TOUTES LES DONNEES</info>, y compris celles déjà envoyées.", OutputInterface::VERBOSITY_VERBOSE);
        }
        $listeIdsANePasImporter = $this->recupererLaListeDesIdsANePasImporter();
        $idMinimum = $this->recupererLIdMinimumAImporter(); // Cette valeur est voué à évoluer.
        $this->nombreElementsDejaEnvoye = 0;
        while (true) {
            $limit = $this->nombreElementsParPaquet;
            if (!empty($this->nombreElementsRestant)) {
                $nombreDeDonneesRestantesAImporter = $this->nombreElementsRestant - $this->nombreElementsDejaEnvoye;
                if ($nombreDeDonneesRestantesAImporter < $this->nombreElementsParPaquet) {
                    $limit = $nombreDeDonneesRestantesAImporter;
                }
            }
            $donnees = $this->selectionDonneesAExporter($idMinimum, $listeIdsANePasImporter, $limit);
            if (empty($donnees)) {
                $this->output->writeln("<info>Pas ou plus de données à traiter</info>", OutputInterface::VERBOSITY_VERBOSE);
                break;
            }
            $paquetExport = [];
            foreach ($donnees as $row) {
                $paquetExport[] = $this->formatageDonnees($row);
                $idMinimum = $row["idExterne"];
            }
            $serviceHttp = $this->getContainer()->get('outils.httpservice.post');
            $serviceHttp->enableHttpBuildQuery();
            $url = $this->getContainer()->getParameter("starkerxp.api.datas");
            $retour = $serviceHttp->envoyer($url, ['batch' => $paquetExport]);
            $retourJson = json_decode($retour, true);
            if (!empty($retourJson) && !$retourJson['error']) {
                $this->output->writeln("<info>Réussit</info>", OutputInterface::VERBOSITY_VERBOSE);
            } else {
                if (!empty($retourJson) && $retourJson['error']) {
                    $this->output->writeln("<info>Échec</info>", OutputInterface::VERBOSITY_VERBOSE);
                    foreach ($retourJson['errors'] as $erreur => $nombre) {
                        $this->output->writeln("\t<info>".$nombre."</info> ".$erreur, OutputInterface::VERBOSITY_VERBOSE);
                    }
                } else {
                    $this->output->writeln("<error>Une erreur est survenue</error>");
                    $this->output->writeln($retour);
                    return false;
                }
            }
            $this->nombreElementsDejaEnvoye += count($donnees);
            if (!empty($this->nombreElementsRestant) && $this->nombreElementsDejaEnvoye >= $this->nombreElementsRestant) {
                $this->output->writeln("<info>[INFO]</info>On n'importe pas plus de données", OutputInterface::VERBOSITY_VERBOSE);
                break;
            }
        }
    }

    public function recupererLaListeDesIdsANePasImporter()
    {
        if (!$this->modeSelection || $this->force) {
            return [];
        }
        // gestion pour le mode date && trigger.
        $listeIds = $this->recupererListeIds();

        return $listeIds;
    }

    protected abstract function recupererListeIds();


    public function recupererLIdMinimumAImporter()
    {
        if ($this->force) {
            return 1;
        }
        // Dans le cas ou l'id est saisi et qu'il s'agit du mode par défaut.
        if (empty($this->modeSelection)) {
            if ((int)$this->input->getOption("id")) {
                return $this->input->getOption("id");
            }

            // On récupère directement dans la base de données / ou webservice
            return $this->recupererIdMininimum();
        }

        return 1;
    }

    protected abstract function recupererIdMininimum();

    protected abstract function selectionDonneesAExporter($idMinimum, $listeIdsANePasImporter, $limit);

    protected abstract function formatageDonnees($row);

    protected function configure()
    {
        parent::configure();
        $this->addOption('mode', '', InputOption::VALUE_OPTIONAL, "[|trigger|date] permet de choisir le mode.")
            ->addOption('nombreElements', '', InputOption::VALUE_OPTIONAL, "définit le nombre d'éléments maximum à importer")
            ->addOption('nombreParPaquet', '', InputOption::VALUE_OPTIONAL, "définit le nombre d'éléments à envoyer par paquet", 50)
            //
            ->addOption('force', '', InputOption::VALUE_NONE, 'Permet de reimporter les données; même celles déjà importées')// Depends du mode choisis.
            //
            ->addOption('id', '', InputOption::VALUE_OPTIONAL, "permet de définir à partir de quel id inclus on importe les données")
            ->addOption('nombreDeMinutes','',InputOption::VALUE_OPTIONAL,"permet de modifier le nombre de minute necessaire pour être présent dans la sélection, par défaut 5",5)
            ->addOption(
                'nombreDeJours',
                '',
                InputOption::VALUE_OPTIONAL,
                "définit le nombre de jours à traiter. Si jourMinimum et jourMaximum sont définis la valeur sera le différenciel",
                3
            )
            ->addOption('jourMinimum', '', InputOption::VALUE_OPTIONAL, "définit le premier jour à importer")
            ->addOption('jourMaximum', '', InputOption::VALUE_OPTIONAL, "définit le dernier jour à importer, par défaut importe jusqu'à hier inclus");
    }


    /**
     * Permet de formatter les données au format attendu.
     *
     * @param $idExterne
     * @param $type
     * @param $origine
     * @param array $data
     * @param array $extra
     *
     * @return array
     */
    protected function genererTableauExport($idExterne, $type, $origine, array $data, array $extra = [])
    {
        $export = [
            "idExterne" => $idExterne,
            "type"      => $type,
            "origine"   => $origine,
            "data"      => json_encode($data),
        ];

        return $export;
    }
}
