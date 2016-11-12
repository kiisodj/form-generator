<?php
/**
 * Created by PhpStorm.
 * User: si
 * Date: 05/07/2016
 * Time: 13:54
 */

namespace Aerian\FormGenerator;

use Aerian\FormGenerator\FieldGenerator\FieldGeneratorInterface;

interface HasFieldGenerator
{
    /**
     * @return FieldGeneratorInterface
     */
    public function getFieldGenerator();
}