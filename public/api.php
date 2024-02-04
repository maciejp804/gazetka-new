<?php
// Your OpenAI API key
$apiKey = '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';
$shop = 'Dino';
// The prompt you want to send

// Data array
$messages = [
    [
        'role' => 'user',
        'content' => 'Napisz tekst produktyowy na temat masła w sieci handlowej '.$shop.' tekst ma posiadać ok 500 słów, być po polsku i mieć
        charakter profesionalny zaiwrać informację o wartościach odżywczych, wymieniać z czego jest wyprodukowane oraz jakie są jego rodzaje.
         Słowa kuczowe do artykułu to: masło, '.$shop.', promocje '.$shop.', masło w gazetce '.$shop.'. tekst powwinien
         posiadać narówki oraz paragrafy a słowa kluczowe wytuszczone. Użyj znaczników html w tekście do ich oznaczenia.
         Kolejny tekst to wstęp do teksu powyżej na ok 500 znaków ze spacjami oraz ostatni tekst to faq do powyższego tesktu'
    ]
    // Add more messages as needed
];

// Data array
$data = [
    'model' => 'gpt-3.5-turbo', // Use the correct model for chat
    'messages' => $messages
];
// API endpoint for ChatGPT
$apiUrl = 'https://api.openai.com/v1/chat/completions';

// Initialize cURL
$ch = curl_init($apiUrl);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $apiKey
]);

// Execute the POST request and get the response
$response = curl_exec($ch);

// Close cURL
curl_close($ch);

// Decode the JSON response
$result = json_decode($response, true);

// Print the response (or handle it as per your need)
echo '<pre>'; print_r($result); echo '</pre>';
?>
