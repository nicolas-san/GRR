<?php
/**
 * @author Bouteillier Nicolas <contact@kaizendo.fr>
 * Date: 17/09/15
 */
namespace Grr\Event;

use Symfony\Component\EventDispatcher\Event;


class EditEntryEvent extends Event
{
    protected $test;

    public function __construct()
    {
        $this->test = "test";
    }

    public function getOrder()
    {
        return $this->test;
    }
}