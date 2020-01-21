<?php

namespace Src;

use SplFileObject;

/**
 * Is used to persist given array data into csv file
 * filename depends on date (__construct)
 *
 * Class CsvUtility
 * @package Src
 */
class CsvUtility
{
    protected array $data;
    protected string $filename;

    public function __construct(array $data)
    {
        $this->data = $data;
        $date = date('Y-m-d');
        $this->filename = "domain_data${date}.csv";
    }

    /**
     * Persists given data array into csv file
     */
    public function __invoke(): void
    {
        if (count($this->data)) {
            $file = new SplFileObject($this->filename, 'w');
            $file->fputcsv(array_keys($this->data[0]));

            foreach ($this->data as $fields) {
                $file->fputcsv($fields);
            }
        }
    }
}