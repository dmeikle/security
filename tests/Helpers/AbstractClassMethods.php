<?php
/**
 * Elentra ME [https://elentra.org]
 *
 * Copyright 2019 Queen's University or its assignee ("Queen's"). All Rights Reserved.
 *
 * This work is subject to Community Licenses ("CL(s)") between Queen's and its various licensee's,
 * respectively, and may only be viewed, accessed, used, reproduced, compiled, modified, copied or
 * exploited (together "Used") in accordance with a CL. Only Queen's or its licensees and their
 * respective Authorized Developers may Use this work in accordance with a CL. If you are not an
 * Authorized Developer, please contact Queen's University (at cla@elentra.org) or its applicable
 * licensee to review the rights and obligations under the applicable CL and become an Authorized
 * Developer before Using this work.
 *
 * @author    Organization: Quantum Unit
 * @author    Developer: Joydip Deb <joydip.debe@elentra.com>
 * @copyright Copyright 2022 Elentra Corporation. All Rights Reserved.
 */

namespace Tests\Helpers;

use ReflectionClass;
use ReflectionException;

/**
 * A convenience class used to do the PHPUnit
 * test in abstract class and its methods
 */
trait AbstractClassMethods
{
    /**
     * This function makes protected abstract function accessible
     * for testing purpose and returns the value.
     *
     * @param $class_obj
     * @param array $method_name_with_args expects method names and method parameter
     * as key => value pair. i.e;
     *
     * [
     * 'example_method_test_1' => [param1, param2, param3],
     * 'example_method_test_2' => [param1]
     * ]
     *
     * @return array
     * @throws ReflectionException
     */
    public function abstractMethodAccessibleHelper($class_obj, array $method_name_with_args): array
    {
        $return_results = [];
        $class = new ReflectionClass(get_class($class_obj));

        if ($method_name_with_args) {
            foreach ($method_name_with_args as $method_name => $args) {
                $method = $class->getMethod($method_name);
                $method->setAccessible(true);
                $return_results[$method_name] = $method->invokeArgs($class_obj, $args);
            }
        }

        return $return_results;
    }
}
