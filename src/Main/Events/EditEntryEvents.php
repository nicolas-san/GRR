<?php
/**
 * @author Bouteillier Nicolas <contact@kaizendo.fr>
 * Date: 17/09/15
 */
namespace Grr\Event;

final class EditEntryEvents
{
    /**
     * L'évènement editEntry.form.before est lancé juste avant la balise form
     *
     * Le « listener » de l'évènement reçoit une instance de
     * Acme\StoreBundle\Event\FilterOrderEvent
     *
     * @var string
     */
    const EDITENTRY_FORM_BEFORE = 'editentry.form_before';
}