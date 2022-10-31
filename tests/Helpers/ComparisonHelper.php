<?php

namespace Tests\Helpers;

trait ComparisonHelper
{
    /**
     * Compares the resulting json to the expected json file but ignores
     * passed in fields since they change all the time
     *
     * @param  array       $response
     * @param  array|null  $ignorableColumns
     *
     * @return void
     */
    protected function assertStringEquals(string $response): void
    {
        //first, generate the output from the test results
        $this->generate($response, $this->SUFFIX_RECEIVED, 3);
        $uri = $this->getFullFilename(2);

        try {
            $expected = (file_get_contents(__PHPUNIT_FILE_PATH . $uri . '.'
                . $this->SUFFIX_EXPECTED . '.txt'));

            $this->assertEquals($expected, $response, 'string does not match');
        } catch (\Exception $exception) {
            //most likely the file does not exist
            //TODO: fail this test but the message should indicate the expected
            //file does not exist
            var_dump($exception->getMessage());
            $this->fail($exception->getMessage());
        }
    }
}
