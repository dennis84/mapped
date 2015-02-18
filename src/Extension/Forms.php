<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;
use Mapped\Events;

/**
 * Forms.
 */
class Forms implements ExtensionInterface
{
    /**
     * Returns a form.
     *
     * @param Mapping $mapping The mapping object
     *
     * @return Form
     */
    public function form(Mapping $mapping)
    {
        $children = array_map(function (Mapping $child) {
            return $child->form();
        }, $mapping->getChildren());

        $form = new Form($mapping, $children);
        $emitter = $mapping->getEmitter();

        $emitter->on(Events::APPLIED, function ($data) use ($form) {
            $form->setData($data->getResult());
            $form->setValue($data->getInput());
            $form->setErrors($data->getErrors());
        });
        
        $emitter->on(Events::UNAPPLIED, function ($data) use ($form) {
            $form->setValue($data->getResult());
        });

        return $form;
    }
}
