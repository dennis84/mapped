<?php

namespace Mapped\Extension;

use Mapped\ExtensionInterface;
use Mapped\Mapping;
use Mapped\Events;
use Mapped\Data;

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
    public function form(Mapping $mapping, $path = [])
    {
        $children = [];
        foreach ($mapping->getChildren() as $name => $child) {
            $children[$name] = $child->form(array_merge($path, [$name]));
        }

        $form = new Form($mapping, $children, $path);
        $emitter = $mapping->getEmitter();

        $emitter->on(Events::APPLIED, function (Data $data) use ($form) {
            $form->setData($data->getResult());
            $form->setValue($data->getInput());
            $form->setErrors($data->getErrors());
        });

        $emitter->on(Events::UNAPPLIED, function (Data $data) use ($form) {
            $form->setValue($data->getResult());
        });

        return $form;
    }
}
