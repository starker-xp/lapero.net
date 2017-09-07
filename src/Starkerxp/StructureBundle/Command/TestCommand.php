<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 07/02/2017
 * Time: 00:40
 */

namespace Starkerxp\StructureBundle\Command;

use Starkerxp\LeadBundle\Form\Type\LeadType;

//I'm includng the yml dumper. Then :

class TestCommand extends AbstractCommand
{

    public function traitement()
    {




        $r = $this->getContainer()->get('form.registry');
            $r = $r->getType("");
        dump($r);
        //exit;

        //$form = $this->getContainer()->get('form.factory')->create(LeadType::class, null, ['formId' => 1]);

    }

    protected function configure()
    {
        $this->setName('test');
    }


}
