<?php

declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use DOMXPath;

class HttpService
{

    const URL = "http://m.olx.ua/api/v1/offers/";

    public function getDocumentByUrl(string $url): DOMDocument|null
    {
        $html = file_get_contents($url);
        $doc = new DOMDocument();
        libxml_use_internal_errors(true);

        if ($doc->loadHTML($html)) {
            return $doc;
        }

        return null;
    }

    public function getId(DOMDocument $document): string|null
    {
        $xpath = new DOMXPath($document);

        foreach ($xpath->query('//div[@data-cy="ad-footer-bar-section"]') as $rowNode) {
            foreach ($rowNode->childNodes as $item) {
                if (str_contains($item->nodeValue, "ID: ")) {
                    return substr($item->nodeValue, 4);
                }
            }
        }

        return null;
    }

    public function getDataByUrl(string $url)
    {
        $id = $this->getId($this->getDocumentByUrl($url));

        $content = file_get_contents("http://m.olx.ua/api/v1/offers/" . $id);

        if ($content !== null) {
            return json_decode($content);
        }

        return null;
    }

    public function getHost(string $url)
    {
        $parsedUrl = parse_url(self::URL);

        if (isset($parsedUrl['host'])) {
            $hostParts = explode('.', $parsedUrl['host']);

            return array_pop($hostParts);
        } else {
            return false;
        }
    }
}
