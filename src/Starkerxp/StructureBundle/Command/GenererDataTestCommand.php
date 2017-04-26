<?php

namespace Starkerxp\StructureBundle\Command;


use Symfony\Component\Yaml\Yaml;

/**
 * Class GenererDataTestCommand
 */
class GenererDataTestCommand extends AbstractCommand
{
    public function nomBaseDeDonnee()
    {
        return $this->getContainer()->getParameter("database_name");
    }

    public function traitement()
    {
        if ($this->getContainer()->get('kernel')->getEnvironment() == 'prod') {
            $this->output->writeln("<error>Cette commande ne peut être lancé en environnement de production</error>");
            return false;
        }
        $meta = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
        // On récupère la liste des entites en fonction des nom de tables supposés.
        $namespaces = [];
        foreach ($meta as $m) {
            if ($m->isMappedSuperclass) {
                continue;
            }
            if (count($m->subClasses)) {
                $namespaces[$m->getName()] = [
                    'nomTable' => $m->getTableName(),
                ];
                continue;
            }
            $listeDesColonnes = array_flip($m->columnNames);
            $listeDesChamps = [];
            foreach ($listeDesColonnes as $column => $field) {
                $listeDesChamps[$column] = [
                    'field' => $field,
                    'type'  => $m->getFieldMapping($field)['type'],
                ];
            }
            $mapping = $m->getAssociationMappings();
            foreach ($mapping as $clefJointure => $detail) {
                if (!array_key_exists("sourceToTargetKeyColumns", $detail)) {
                    continue;
                }
                $column = array_keys($detail['sourceToTargetKeyColumns'])[0];
                $listeDesChamps[$column] = [
                    'field'                    => $detail['fieldName'],
                    'type'                     => 'integer',
                    'sourceToTargetKeyColumns' => $detail['sourceToTargetKeyColumns'][$column],
                    'targetEntity'             => $detail['targetEntity'],
                ];
            }
            $namespaces[$m->getName()] = [
                'nomTable'            => $m->getTableName(),
                'listeDesChamps'      => $listeDesChamps,
                'discriminatorValue'  => $m->discriminatorValue,
                'discriminatorColumn' => !empty($m->discriminatorValue) ? $m->discriminatorColumn['name'] : null,
            ];
        }
        ksort($namespaces);
        $contenu = "";
        foreach ($namespaces as $namespace => $data) {
            if (empty($data['listeDesChamps'])) {
                continue;
            }
            $export = [];
            $listeDesChamps = $data['listeDesChamps'];
            $nomTable = $data['nomTable'];
            $where = (!empty($data['discriminatorValue']) ? $data['discriminatorColumn']."='".$data['discriminatorValue']."'" : "1=1");
            $strChamp = implode(",", array_keys($listeDesChamps));
            $sql = "SELECT ".$strChamp." FROM ".$nomTable." WHERE ".$where;
            $resultats = $this->getConnection()->fetchAll($sql);
            if (empty($resultats)) {
                continue;
            }
            foreach ($resultats as $key => $row) {
                $dataExport = [];
                foreach ($row as $element => $value) {
                    if ($element == 'id') {
                        continue;
                    }
                    $field = $listeDesChamps[$element]['field'];
                    if (!empty($listeDesChamps[$element]['targetEntity'])) {
                        $value = empty($value) ? null : strtolower("@".$namespaces[$listeDesChamps[$element]['targetEntity']]['nomTable'])."_".$value;
                        $dataExport[$listeDesChamps[$element]['field']] = $value;
                        continue;
                    }
                    $dataExport[$field] = $this->formatValue($listeDesChamps[$element]['type'], $value);
                }
                $export[strtolower($nomTable).'_'.$row['id']] = $dataExport;
            }
            $contenu .= Yaml::dump([$namespace => $export,], 2, 4)."\n";
        }
        echo $contenu;
    }

    public function formatValue($type, $value)
    {
        if (in_array($type, ['datetime', 'date']) && !empty($value)) {
            return $value = '<(new \DateTime("'.$value.'"))>';
        }
        if (in_array($type, ['json_array'])) {
            return json_decode($value, true);
        }
        if (in_array($type, ['array'])) {
            return unserialize($value);
        }

        return $value;
    }

    protected function configure()
    {
        parent::configure();
        $this->setName('starkerxp:dump-database')
            ->setDescription("Exporte une base de données de 'dev' en fixtures yml.")
            ->setHelp(
                "Cette commande est là pour réaliser un fichier yml destiné aux tests fonctionnels via phpunit couplé avec nelmio/alice.
On peut donc aisément recréer l'environnement souhaité avant le lancement des tests unitaires.
Attention !!! Cette commande n'est pas destiné à fonctionner sur un gros volume de données."
            );
    }
}
