<?php
/**
 * @author Bouteillier Nicolas <contact@kaizendo.fr>
 * Date: 17/09/15
 */
namespace Grr\Event;

use Symfony\Component\EventDispatcher\Event;


class EditEntryForm extends Event
{
    private $tpl;

    public function __construct(array $tpl)
    {
        $this->tpl = $tpl;
    }

    /**
     * @return array
     */
    public function getTpl()
    {
        return $this->tpl;
    }

    /**
     * @param array $tpl
     */
    public function setTpl($tpl)
    {
        $this->tpl = $tpl;
    }


}