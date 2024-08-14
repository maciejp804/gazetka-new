<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OCR Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
<h1>Test OCR Endpoint</h1>
<form id="ocrForm" enctype="multipart/form-data">
    <label for="pdfFile">Wybierz plik PDF:</label><br><br>
    <input type="file" id="pdfFile" name="pdfFile" accept="application/pdf, application/json"><br><br>
    <button type="button" onclick="sendPdf()">Wyślij</button>
    <button type="button" onclick="resultJson()">Json</button>
</form>

<h2>Wynik:</h2>
<pre id="result"></pre>

<script>
    async function sendPdf() {
        const pdfFile = document.getElementById('pdfFile').files[0];
        const resultElement = document.getElementById('result');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const formData = new FormData();
        formData.append('pdfFile', pdfFile);

        try {
            const response = await fetch('/process-ocr', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                resultElement.textContent = JSON.stringify(result, null, 2);
            } else {
                resultElement.textContent = 'Błąd: ' + response.statusText;
            }
        } catch (error) {
            resultElement.textContent = 'Błąd: ' + error.message;
        }
    }

    async function resultJson() {

        const resultElement = document.getElementById('result');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const formData = new FormData();
        formData.append('pdfFile', pdfFile);

        try {
            const response = await fetch('/json-ocr', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                body: ''
            });

            if (response.ok) {
                const result = await response.json();
                resultElement.textContent = JSON.stringify(result, null, 2);
            } else {
                resultElement.textContent = 'Błąd: ' + response.statusText;
            }
        } catch (error) {
            resultElement.textContent = 'Błąd: ' + error.message;
        }
    }
</script>
</body>
</html>
