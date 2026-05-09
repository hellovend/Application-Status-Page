<?php

$name = $_POST['name'] ?? '';
$message = $_POST['message'] ?? '';

$data = [
    'entry.123456789' => $name,
    'entry.987654321' => $message
];

$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-type: application/x-www-form-urlencoded",
        'content' => http_build_query($data)
    ]
];

$context = stream_context_create($options);

$result = file_get_contents(
    'https://docs.google.com/forms/d/e/1FAIpQLSdE8qLFs_VnMr9l3njeIjCdFK7G1P9umoaT4ufDz_UTuhLzpA/formResponse',
    false,
    $context
);

echo "제출 완료";
?>