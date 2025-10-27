<?php
// public/index.php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>URL2QR Generator</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      font-family: system-ui, sans-serif;
      text-align: center;
      margin: 40px;
      background-color: #fafafa;
      color: #222;
    }

    form {
      margin: 20px auto;
      display: inline-flex;
      align-items: center;
      gap: 10px;
      flex-wrap: wrap;
    }

    input[type="text"],
    input[type="number"] {
      padding: 5px;
      font-size: 1rem;
      width: 200px;
    }

    input[type="color"] {
      width: 40px;
      height: 32px;
      border: none;
      cursor: pointer;
    }

    button {
      padding: 6px 12px;
      font-size: 1rem;
      cursor: pointer;
      border-radius: 4px;
      border: 1px solid #aaa;
      background: #eee;
      transition: all 0.2s;
    }

    button:hover {
      background: #ddd;
    }

    .preview {
      margin-top: 20px;
    }

    #qrImage {
      display: block;
      margin: 10px auto;
      border: 1px solid #ccc;
      background: white;
    }

    #downloadBtn {
      background: #47746f;
      color: white;
      border: none;
      margin-left: 10px;
    }

    #downloadBtn:hover {
      background: #365c57;
    }
  </style>
</head>

<body>
  <h1>URL2QR Generator</h1>
  <p>Enter your text or URL below and generate a QR code instantly.</p>

  <!-- Form UI -->
  <form id="qrForm" onsubmit="return false;">
    <input type="text" id="qrText" placeholder="Enter URL or text" value="UNESI URL OVDJE" />
    <label>Pick color: <input type="color" id="qrColor" value="#000000"></label>
    <label>Size (px): <input type="number" id="qrSize" value="300" min="100" max="2000"></label>
    <button id="generateBtn" type="button">Generate</button>
    <button id="downloadBtn" type="button">Download</button>
  </form>

  <!-- Preview -->
  <div class="preview">
    <img id="qrImage" src="" alt="QR preview" width="300" height="300" />
  </div>

  <script>
    const form = document.getElementById('qrForm');
    const img = document.getElementById('qrImage');
    const generateBtn = document.getElementById('generateBtn');
    const downloadBtn = document.getElementById('downloadBtn');

    function buildParams() {
      const text = encodeURIComponent(document.getElementById('qrText').value.trim());
      const color = document.getElementById('qrColor').value.replace('#', '');
      const size = document.getElementById('qrSize').value;
      return {
        text,
        color,
        size
      };
    }

    function updateQR() {
      const {
        text,
        color,
        size
      } = buildParams();
      if (!text) return;
      const url = `qr.php?text=${text}&color=${color}&size=${size}`;
      img.src = url + '&_=' + Date.now(); // force refresh
    }

    function downloadQR() {
      const {
        color,
        size
      } = buildParams();
      let rawText = document.getElementById('qrText').value.trim();
      if (!rawText) return;

      // makni http:// ili https:// s početka
      rawText = rawText.replace(/^https?:\/\//i, '');

      // zamijeni sve što nije slovo, broj, crtica ili točka s _
      let namePart = rawText.replace(/[^a-z0-9\.\-]+/gi, '_')
        .replace(/^_+|_+$/g, '') // makni _ na početku i kraju
        .substring(0, 40); // limit duljine

      if (namePart === '') namePart = 'qr_code';
      const fname = `qr_${namePart}.png`;

      // encodeiraj samo text u URL param
      const textParam = encodeURIComponent(document.getElementById('qrText').value.trim());
      const url = `download.php?text=${textParam}&color=${color}&size=${size}&filename=${encodeURIComponent(fname)}`;

      // update tooltip
      document.getElementById('downloadBtn').title = `Save as: ${fname}`;

      window.location.href = url; // pokreće download
    }




    generateBtn.addEventListener('click', updateQR);
    downloadBtn.addEventListener('click', downloadQR);

    // auto-generate on load
    updateQR();
  </script>
</body>

</html>