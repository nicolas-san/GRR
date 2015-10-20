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
    private $idSite;

    public function __construct($idSite, $idArea, array $tpl)
    {
        $this->tpl = $tpl;
        $this->idArea = $idArea;
        $this->idSite = $idSite;
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

    /**
     * @return mixed
     */
    public function getIdSite()
    {
        return $this->idSite;
    }

    /**
     * @param mixed $idSite
     */
    public function setIdSite($idSite)
    {
        $this->idSite = $idSite;
    }


}