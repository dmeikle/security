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
 * @author    Developer: David Meikle <dave.meikle@elentra.com>
 * @copyright Copyright 2022 Elentra Corporation. All Rights Reserved.
 */

namespace Tests\Helpers;

/**
 * A convenience class used to compare the json result from a call
 * to the expected output so we don't have to bother traversing
 * the heirarchy of a json string to verify individual values
 */
trait JsonResponseHelper
{
    private $dateKeys = [
        'created_at',
        'updated_at',
        'deleted_at',
        'created_date',
        'updated_date',
        'deleted_date',
        'date',
        'createdDate',
        'updatedDate',
        'deletedDate',
        'openDate',
        'closeDate',
        'availableOn',
        'start_date',
        'end_date',
        'startDate',
        'endDate',
    ];

    /**
     * Compares the resulting json to the expected json file but ignores
     * date fields since they change all the time based on seed data
     * import times
     *
     * @param  string  $json
     *
     * @return void
     */
    public function assertJsonEqualsIgnoreDates(array $response): void
    {

        //first, generate the output from the test results
        $this->generate($response, $this->SUFFIX_RECEIVED, 3);
        $uri = $this->getFullFilename(2);

        try {
            $expected = (file_get_contents(__PHPUNIT_FILE_PATH . $uri . '.'
                . $this->SUFFIX_EXPECTED . '.txt'));
            $expected = $this->stripFields($expected, $this->dateKeys);

            $json = json_encode($response, JSON_PRETTY_PRINT);

            $json = $this->stripFields($json, $this->dateKeys);

            // return array_diff(json_decode($expected,true), json_decode( $expected, true));
            $this->assertJsonStringEqualsJsonString($expected, $json,
                'json does not match');
        } catch (\Exception $exception) {
            //most likely the file does not exist
            //TODO: fail this test but the message should indicate the expected
            //file does not exist
            var_dump($exception->getMessage());
            $this->fail($exception->getMessage());
        }
    }

    /**
     * Compares the resulting json to the expected json file but ignores
     * passed in fields since they change all the time
     *
     * @param  array       $response
     * @param  array|null  $ignorableColumns
     *
     * @return void
     */
    protected function assertJsonEquals(array $response, array $ignorableColumns = null): void
    {

        //first, generate the output from the test results
        $this->generate($response, $this->SUFFIX_RECEIVED, 3);
        $uri = $this->getFullFilename(2);

        try {
            $expected = (file_get_contents(__PHPUNIT_FILE_PATH . $uri . '.'
                . $this->SUFFIX_EXPECTED . '.txt'));

            $actualColumnsToIgnore = ($ignorableColumns === null) ? $this->dateKeys : array_merge($this->dateKeys, $ignorableColumns);
            $expected = $this->stripFields($expected, $actualColumnsToIgnore);

            $json = json_encode($response, JSON_PRETTY_PRINT);

            $json = $this->stripFields($json, $actualColumnsToIgnore);

            // return array_diff(json_decode($expected,true), json_decode( $expected, true));
            $this->assertJsonStringEqualsJsonString($expected, $json,
                'json does not match');
        } catch (\Exception $exception) {
            //most likely the file does not exist
            $this->fail($exception->getMessage());
        }
    }

    /**
     * @param string $json
     * @param array|null $ignorableColumns
     * @return false|string
     */
    private function stripFields(string $json, array $ignorableColumns = null)
    {
        if ($json === null || $ignorableColumns === null) {
            return $json;
        }
        $expectedObj = json_decode($json, true);
        if (is_array($expectedObj)) {
            foreach ($expectedObj as $key => &$value) {
                if (!is_array($value)) {
                    if (in_array($key, $ignorableColumns)) {
                        unset($expectedObj[$key]);
                    }
                } else {
                    $value = $this->stripFields(json_encode($value), $ignorableColumns);
                }
            }
        }

        return json_encode($expectedObj);
    }
}
