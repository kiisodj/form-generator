<?php
/**
 * Created by PhpStorm.
 * User: si
 * Date: 05/07/2016
 * Time: 13:55
 */

namespace Aerian\FormGenerator\FieldGenerator;

use Kris\LaravelFormBuilder\Form;

interface FieldGeneratorInterface
{
    public function addFields($model, Form $form);
}