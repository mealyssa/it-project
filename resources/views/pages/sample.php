<?php
require_once './TesseractOCR/TesseractOCR.php';
$tesseract = new TesseractOCR('test.png');
$text = $tesseract->recognize();
echo PHP_EOL, "The recognized text is:", $text, PHP_EOL;
echo "545";