<?php
// public/index.php
// Main UI — form + preview + color picker
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>URL2QR Generator</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>URL2QR Generator</h1>
  <p>Enter your text or URL below and generate a QR code instantly.</p>

  <!-- Form UI -->
  <form id="qrForm" onsubmit="return false;">
    <input type="text" id="qrText" placeholder="Enter URL or text" value="https://dd-lab.hr" />
    <label>Pick color: <input type="color" id="qrColor" value="#000000"></label>
    <label>Size (px): <input type="number" id="qrSize" value="300" min="100" max="2000"></label>
    <button id="generateBtn">Generate</button>
  </form>

  <!-- Preview -->
  <div class="preview">
    <img id="qrImage" src="" alt="QR preview" />
    <a id="downloadLink" href="#" download="qrcode.png">Download PNG</a>
  </div>

  <script>
    const form = document.getElementById('qrForm');
    const img = document.getElementById('qrImage');
    const dl = document.getElementById('downloadLink');

    form.addEventListener('input', updateQR);
    form.addEventListener('submit', updateQR);

    function updateQR() {
      const text = encodeURIComponent(document.getElementById('qrText').value);
      const color = document.getElementById('qrColor').value.replace('#','');
      const size = document.getElementById('qrSize').value;
      const url = `qr.php?text=${text}&color=${color}&size=${size}`;
      img.src = url + '&_=' + Date.now();
      dl.href = url;
    }

    updateQR();
  </script>
</body>
</html>
