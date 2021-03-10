<?php
namespace App\Services\Handlers;

class JSONHandler
{
    private $jsonPath;
    public function __construct(string $jsonPath)
    {
        $this->jsonPath = $jsonPath;
    }

    public function store(string $key, array $data)
    {
        $file = fopen($this->jsonPath . $key, "w+");
        if (is_null($file)) {
            return;
        }

        fwrite($file, json_encode($data));
        fclose($file);
    }

    public function read(string $key)
    {
        $file = fopen($this->jsonPath . $key, "r");

        if (!$file) {
            return array();
        }

        $records = fread($file, filesize($this->jsonPath . $key));
        fclose($file);

        return json_decode($records, true);
    }

    public function getFiles(string $key)
    {
        return scandir($this->jsonPath);
    }
}
