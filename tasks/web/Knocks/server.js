const express = require('express');
const cookieParser = require('cookie-parser');
const path = require('path');

const app = express();
const PORT = 3000;

app.use(cookieParser());
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

app.use(express.static(path.join(__dirname, 'public')));

const FLAG_PARTS = [
  "h3x1514{Y0u_",
  "kn0w_h0w_",
  "t0_kn0ck}"
];

app.get('/', (req, res) => {
  res.sendFile(path.join(__dirname, 'public', 'index.html'));
});

app.all('/flag', (req, res) => {
  let stage = parseInt(req.cookies.stage || "0");

  if (req.method === 'POST' && stage === 0) {
    res.cookie('stage', '1', { httpOnly: true });
    return res.json({
      message: "First knock accepted",
      part: FLAG_PARTS[0]
    });
  }

  if (req.method === 'GET' && stage === 1) {
    res.cookie('stage', '2', { httpOnly: true });
    return res.json({
      message: "Second knock accepted",
      part: FLAG_PARTS[1]
    });
  }

  if (req.method === 'POST' && stage === 2) {
    res.cookie('stage', '3', { httpOnly: true });
    return res.json({
      message: "Final knock accepted",
      part: FLAG_PARTS[2]
    });
  }

  res.status(403).json({
    error: "Nothing happens...",
    hint: "Wrong order or wrong method"
  });
});

app.listen(PORT, () => {
  console.log(`CTF on http://localhost:${PORT}`);
});
