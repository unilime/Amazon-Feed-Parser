<?php

namespace Src;

use Exception;
use SimpleXMLElement;

/**
 * Is used to parse xml file or link that leads to xml file into array and return it
 *
 * Class Parser
 * @package Src
 */
class Parser
{
    protected SimpleXMLElement $xmlObject;

    protected array $parsedData = [];

    /**
     * Parser constructor.
     * @param  string  $filePath
     * @throws Exception
     */
    public function __construct(string $filePath)
    {
        if (!file_exists($filePath) and strpos($filePath, 'https') === false) {
            throw new Exception('File not found');
        }

        $this->xmlObject = new SimpleXMLElement(file_get_contents($filePath));
    }

    /**
     * Parses given xml object file into array
     *
     * @return array
     */
    public function __invoke(): array
    {
        $items = $this->xmlObject->channel->item;

        foreach ($items as $item) {
            $namespaces = (object)$item->getNamespaces(true);
            $children = $item->children($namespaces->amzn);

            foreach ($children->products as $products) {
                foreach ($products as $product) {
                    $dataItem = [
                        'ASIN' => $this->parseAsin($product->productURL),
                        'URL' => trim($item->link),
                        'Amazon Url' => trim($product->productURL),
                        'Product Name' => trim($product->productHeadline),
                        'Amazon Introtext' => trim($children->introText),
                        'Amazon Introtext COUNT' => strlen($children->introText),
                        'Amazon Product Summary' => trim($product->productSummary),
                        'Amazon Product Summary COUNT' => strlen($product->productSummary),
                        'Amazon Award' => trim($product->award),
                        'Amazon Award COUNT' => strlen($product->award)
                    ];

                    $this->parsedData[] = $dataItem;
                }
            }
        }

        return $this->parsedData;
    }

    /**
     * Parse product code by its url
     *
     * @param  string  $productUrl
     * @return string
     */
    private function parseAsin(string $productUrl): string
    {
        return substr($productUrl, strpos($productUrl, 'dp/') + 3);
    }
}