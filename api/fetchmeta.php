<?php


use DiDom\Document;

function fetchMeta(string $url, bool $min = true): array
{
    $document = new Document($url, true);
    $metadata = $document->find("meta");

    $data = [];
    foreach ($metadata as $element) {
        $name = $element->getAttribute("name");
        $property = $element->getAttribute("property");
        $content = $element->getAttribute("content");

        // Add the extracted metadata to the data array
        if ($min) {
            $key = ($name) ? $name : $property;
            if ($key) {
                $data[$key] = $content;
            }
        } else {
            $data[] = [
                "name" => $name,
                "property" => $property,
                "content" => $content,
            ];
        }
    }

    return $data;
}


// Get Values from the POST request
$url = $_POST['url'];
$min = isset($_POST['min']) ? true : false;

// Validate the URL
if (empty($url)) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'URL parameter is missing.']);
    exit;
}

$data = fetchMeta($url, $min);
echo json_encode($data, JSON_PRETTY_PRINT);
