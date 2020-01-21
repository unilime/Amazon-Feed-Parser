<?php

use Src\{Parser, CsvUtility};

require __DIR__.'/vendor/autoload.php';

$data = (new Parser('https://stylecaster.com/feeds/amazon'))();
(new CsvUtility($data))();