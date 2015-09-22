<?php
/**
 * @author Bouteillier Nicolas <contact@kaizendo.fr>
 * Date: 17/09/15
 */
namespace Grr\Event;

final class EditEntryEvent
{
    /**
     * L'évènement editEntry.form.before est lancé juste avant la balise form
     *
     * Le « listener » de l'évènement reçoit une instance de
     * Grr\Event\EditEntryForm
     *
     * @var string
     */
    const EDITENTRY_FORM_BEFORE = 'editentry.form_before';
    const EDITENTRY_FORM_AFTER = 'editentry.form_after';
    const EDITENTRY_FORM_INSIDE_START = 'editentry.form_inside_start';
    const EDITENTRY_FORM_INSIDE_END = 'editentry.form_inside_stop';
    const EDITENTRY_FORM_INSIDE_PLUGIN_AREA = 'editentry.form_inside_plugin_area';
}