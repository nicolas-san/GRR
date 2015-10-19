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
    private $idArea;

    public function __construct($idArea, array $tpl)
    {
        $this->tpl = $tpl;
        $this->idArea = $idArea;
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

    /**
     * @return mixed
     */
    public function getIdArea()
    {
        return $this->idArea;
    }

    /**
     * @param mixed $idArea
     */
    public function setIdArea($idArea)
    {
        $this->idArea = $idArea;
    }


}