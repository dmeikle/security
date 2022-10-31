<?php

namespace Tests\Helpers;

use Entrada\Modules\Locations\Http\Requests\DisciplineInstructorRequest;

trait IOHelper
{
    protected $SUFFIX_EXPECTED = 'expected';
    protected $SUFFIX_RECEIVED = 'received';

    /**
     * Saves the json to a file that matches the name of the test file class name
     *
     * @param  string  $json
     *
     * @return void
     */
    public function generate(
        $response,
        string $suffix = 'expected',
        int $depth = 2
    ) {
        $uri = $this->getFullFilename($depth);

        $this->createDirectoryPath(pathinfo(__PHPUNIT_FILE_PATH . $uri,
            PATHINFO_DIRNAME));
        if (is_array($response)) {
            file_put_contents(__PHPUNIT_FILE_PATH . "$uri.$suffix.txt", json_encode($response, JSON_PRETTY_PRINT));
        } else {
            file_put_contents(__PHPUNIT_FILE_PATH . "$uri.$suffix.txt", $response);
        }
    }

    /**
     * Get the full name of the file
     *
     * @param  int  $depth
     *
     * @return string
     */
    private function getFullFilename(int $depth): string
    {
        $subfolder = $this->getSubfolder();
        $className = (new \ReflectionClass($this))->getShortName();
        $callingFunction = debug_backtrace()[$depth]['function'];

        return $subfolder . DIRECTORY_SEPARATOR . $className . DIRECTORY_SEPARATOR . $callingFunction;
    }

    /**
     * Get the subdirectory so we can create a directory path
     *
     * @return string
     */
    private function getSubfolder(): string
    {
        $namespace = (new \ReflectionClass($this))->getNamespaceName();
        $bits = explode('\\', $namespace);
        array_shift($bits);

        return implode(DIRECTORY_SEPARATOR, $bits); //$bits[1] . DIRECTORY_SEPARATOR . $bits[2];
    }

    /**
     * Ensures that the path to write to exists
     *
     * @param  string  $path
     *
     * @return void
     */
    protected function createDirectoryPath(string $path)
    {
        try {
            if (!file_exists($path)) {
                var_dump('creating new path ' . $path . "\r\n");
                mkdir($path, 0777, true);
            }
        } catch (\Exception $exception) {
            echo "exception creating path $path\r\n";
            echo $exception->getMessage();
            $this->fail();
        }
    }
}
