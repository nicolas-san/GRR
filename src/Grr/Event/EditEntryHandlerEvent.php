<?php
/**
 * @author Bouteillier Nicolas <contact@kaizendo.fr>
 * Date: 17/09/15
 */
namespace Grr\Event;

final class EditEntryHandlerEvent
{
    /**
     * L'évènement editEntryHandler
     *
     * Le « listener » de l'évènement reçoit une instance de
     * Grr\Event\EditEntryHandlerData
     *
     * @var string
     */
    const EDITENTRYHANDLER_START = 'editentryhandler.start';

}