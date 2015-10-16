<?php
/**
 * @author Bouteillier Nicolas <contact@kaizendo.fr>
 * Date: 17/09/15
 */
namespace Grr\Event;

use Symfony\Component\EventDispatcher\Event;


class EntryEventClass extends Event
{
    private $id;
    private $tpl;

    public function __construct($id, $tpl)
    {
        $this->id = $id;
        $this->tpl = $tpl;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTpl()
    {
        return $this->tpl;
    }

    /**
     * @param mixed $tpl
     */
    public function setTpl($tpl)
    {
        $this->tpl = $tpl;
    }



}