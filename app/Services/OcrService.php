<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public function recognizeText($imagePath)
    {
        $ocr = new TesseractOCR($imagePath);
        return $ocr->run();
    }
}
?>