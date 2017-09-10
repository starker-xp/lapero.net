<?php

namespace Starkerxp\StructureBundle\Command;


use Symfony\Component\Yaml\Yaml;

/**
 * Class GenererDataTestCommand
 */
class GenererDataTestCommand extends LockCommand
{

    /**
     * @return bool
     */
    public function treatment()
    {
        if (preg_match("#prod#", $this->getEnvironment())) {
            $this->output->writeln("<error>Command disallow</error>");

            return false;
        }

        $allMetadata = $this->getEntityManager()->getMetadataFactory()->getAllMetadata();
        // On récupère la liste des entites en fonction des nom de tables supposés.
        $namespaces = [];
        foreach ($allMetadata as $metadata) {
            if ($metadata->isMappedSuperclass) {
                continue;
            }
            if (count($metadata->subClasses)) {
                $namespaces[$metadata->getName()] = [
                    'tableName' => $metadata->getTableName(),
                ];
                continue;
            }
            $columns = array_flip($metadata->columnNames);
            $fields = [];
            foreach ($columns as $column => $field) {
                $fields[$column] = [
                    'field' => $field,
                    'type' => $metadata->getFieldMapping($field)['type'],
                ];
            }
            $mapping = $metadata->getAssociationMappings();
            foreach ($mapping as $detail) {
                if (!array_key_exists("sourceToTargetKeyColumns", $detail)) {
                    continue;
                }
                $column = array_keys($detail['sourceToTargetKeyColumns'])[0];
                $fields[$column] = [
                    'field' => $detail['fieldName'],
                    'type' => 'integer',
                    'sourceToTargetKeyColumns' => $detail['sourceToTargetKeyColumns'][$column],
                    'targetEntity' => $detail['targetEntity'],
                ];
            }
            $namespaces[$metadata->getName()] = [
                'tableName' => $metadata->getTableName(),
                'fields' => $fields,
                'discriminatorValue' => $metadata->discriminatorValue,
                'discriminatorColumn' => !empty($metadata->discriminatorValue) ? $metadata->discriminatorColumn['name'] : null,
            ];
        }
        ksort($namespaces);
        $contenu = "";
        foreach ($namespaces as $namespace => $data) {
            if (empty($data['fields'])) {
                continue;
            }
            $export = [];
            $where = (!empty($data['discriminatorValue']) ? $data['discriminatorColumn']."='".$data['discriminatorValue']."'" : "1=1");
            $query = "SELECT ".implode(",", array_keys($data['fields']))." FROM ".$data['tableName']." WHERE ".$where;
            if (!$resultats = $this->getConnection()->fetchAll($query)) {
                continue;

            }

            foreach ($resultats as $key => $row) {
                $dataExport = [];
                foreach ($row as $element => $value) {
                    if ($element == 'id') {
                        continue;
                    }
                    $field = $data['fields'][$element]['field'];
                    if (!empty($data['fields'][$element]['targetEntity'])) {
                        $value = empty($value) ? null : strtolower("@".$namespaces[$data['fields'][$element]['targetEntity']]['tableName'])."_".$value;
                        $dataExport[$data['fields'][$element]['field']] = $value;
                        continue;
                    }
                    $dataExport[$field] = $this->formatValue($data['fields'][$element]['type'], $value);
                }
                $export[strtolower($data['tableName']).'_'.$row['id']] = $dataExport;
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
            ->setDescription("Extract development database to yaml fixtures.");
    }


}
