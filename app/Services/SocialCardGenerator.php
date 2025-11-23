<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SocialCardGenerator
{
    public function __construct(
        private string $fontBoldPath = 'resources/fonts/Inter-Bold.ttf',
        private string $fontRegularPath = 'resources/fonts/Inter-Regular.ttf',
    ) {}

    /**
     * Generate a card and save to file
     */
    public function make(array $opts): string
    {
        $im = $this->render($opts);

        // save to disk
        $filename = 'social/'.date('Y/m').'/'.Str::uuid().'.jpg';
        $absPath = Storage::disk('public')->path($filename);
        @mkdir(dirname($absPath), 0775, true);
        imagejpeg($im, $absPath, 100);
        imagedestroy($im);

        return $absPath;
    }

    /**
     * Generate a card preview (no saving) & return bytes
     */
    public function stream(array $opts): string
    {
        $im = $this->render($opts);

        ob_start();
        imagejpeg($im, null, 100);
        imagedestroy($im);

        return ob_get_clean();
    }

    /**
     * Draw the card (shared between save & preview)
     * Returns GD resource
     */
    private function render(array $opts)
    {
        $w = 1200; $h = 675;
        $title    = trim($opts['title'] ?? '');
        $subtitle = trim($opts['subtitle'] ?? '');
        $bg       = $opts['bg'] ?? null;
        $logo     = $opts['logo'] ?? null;
        $primary  = $this->hex($opts['primary'] ?? '#0ea5e9');
        $secondary= $this->hex($opts['secondary'] ?? '#0b1220');
        $water    = trim($opts['watermark'] ?? '');
        $twater   = trim($opts['top_watermark'] ?? '');

        // canvas
        $im = imagecreatetruecolor($w, $h);
        imageantialias($im, true);

        // bg solid
        [$r2,$g2,$b2] = $secondary;
        $bgCol = imagecolorallocate($im,$r2,$g2,$b2);
        imagefilledrectangle($im, 0, 0, $w, $h, $bgCol);

        // optional bg image
        if ($bg) {
            $bgPath = $this->ensureLocal($bg);
            $bgImg = $this->loadImage($bgPath);
            if ($bgImg) {
                $bw = imagesx($bgImg); $bh = imagesy($bgImg);
                $scale = max($w/$bw, $h/$bh);
                $nw = (int)($bw*$scale); $nh=(int)($bh*$scale);
                $x = (int)(($w - $nw)/2); $y = (int)(($h - $nh)/2);
                imagecopyresampled($im, $bgImg, $x, $y, 0, 0, $nw, $nh, $bw, $bh);
                imagedestroy($bgImg);

                // dark overlay
                $overlay = imagecolorallocatealpha($im, 0, 0, 0, 80);
                imagefilledrectangle($im, 0, 0, $w, $h, $overlay);
            }
        }

        // gradient ribbon
        [$r1,$g1,$b1] = $primary;
        for ($i=0; $i<$w; $i++) {
            $alpha = 100 - (int)(100 * ($i/$w));
            $col = imagecolorallocatealpha($im, $r1, $g1, $b1, min(127, max(0, 95 + (int)round($alpha * 0.3))));
            imageline($im, $i, 0, $i, $h, $col);
        }

        $padX=72; $padY=72;
        $white = imagecolorallocate($im, 255, 255, 255);
        $muted = imagecolorallocate($im, 229, 231, 235);

        // title
        $titleSize = $this->fitText($title, $this->fontBoldAbs(), 64, 36, $w - 2*$padX, 4);
        $titleLines= $this->wrap($title, $this->fontBoldAbs(), $titleSize, $w - 2*$padX);
        $y = $padY + $titleSize;

        foreach ($titleLines as $line) {
            $bbox = imagettfbbox($titleSize, 0, $this->fontBoldAbs(), $line);
            $lineHeight = ($bbox[1]-$bbox[7]);
            imagettftext($im, $titleSize, 0, $padX, $y, $white, $this->fontBoldAbs(), $line);
            $y += $lineHeight + 12;
        }

        // subtitle
        if ($subtitle !== '') {
            $subSize = 18;
            $subLines = $this->wrap($subtitle, $this->fontRegularAbs(), $subSize, $w - 2*$padX);
            foreach ($subLines as $line) {
                $bbox = imagettfbbox($subSize, 0, $this->fontRegularAbs(), $line);
                $lineHeight = ($bbox[1]-$bbox[7]);
                imagettftext($im, $subSize, 0, $padX, $y, $muted, $this->fontRegularAbs(), $line);
                $y += $lineHeight + 8;
            }
        }

        // logo
        if ($logo && is_file($logoAbs = base_path($logo))) {
            $lg = $this->loadImage($logoAbs);
            if ($lg) {
                $lw=imagesx($lg); $lh=imagesy($lg);
                $targetH = 64; $scale = $targetH / $lh; $tw=(int)($lw*$scale); $th=(int)($lh*$scale);
                imagecopyresampled($im, $lg, $padX, $h - $padY - $th, 0,0, $tw,$th, $lw,$lh);
                imagedestroy($lg);
            }
        }

        // bottom-right watermark
        if ($water !== '') {
            $wmSize = 20;
            $bbox = imagettfbbox($wmSize, 0, $this->fontRegularAbs(), $water);
            $ww = $bbox[2] - $bbox[0]; $wh = $bbox[1]-$bbox[7];
            imagettftext($im, $wmSize, 0, $w-$padX-$ww, $h-$padY, $muted, $this->fontRegularAbs(), $water);
        }

        // top-right watermark
        if ($twater !== '') {
            $wmSize = 12;
            $maxWidth  = min(380, $w - 2 * $padX);
            $lines     = $this->wrap($twater, $this->fontRegularAbs(), $wmSize, $maxWidth);

            $yTop = $padY;
            foreach ($lines as $ln) {
                $bbox = imagettfbbox($wmSize, 0, $this->fontRegularAbs(), $ln);
                $lw   = $bbox[2] - $bbox[0]; $lh   = $bbox[1] - $bbox[7];
                $x = $w - $padX - $lw;
                imagettftext($im, $wmSize, 0, $x, $yTop + $lh, $muted, $this->fontRegularAbs(), $ln);
                $yTop += $lh + 6;
            }
        }

        return $im;
    }

    // ========== helper methods ==========

    private function fontBoldAbs(): string    { return base_path($this->fontBoldPath); }
    private function fontRegularAbs(): string { return base_path($this->fontRegularPath); }

    private function hex(string $hex): array {
        $hex = ltrim($hex, '#');
        if (strlen($hex)===3) $hex = preg_replace('/(.)/','$1$1',$hex);
        return [hexdec(substr($hex,0,2)), hexdec(substr($hex,2,2)), hexdec(substr($hex,4,2))];
    }

    private function loadImage(string $path) {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return match ($ext) {
            'jpg','jpeg' => @imagecreatefromjpeg($path),
            'png'        => @imagecreatefrompng($path),
            'gif'        => @imagecreatefromgif($path),
            default      => null,
        };
    }

    private function ensureLocal(string $path): ?string {
        if (preg_match('#^https?://#i', $path)) {
            $tmp = tempnam(sys_get_temp_dir(), 'bg_');
            $bin = @file_get_contents($path);
            if ($bin === false) return null;
            file_put_contents($tmp, $bin);
            return $tmp;
        }
        $abs = str_starts_with($path, '/') ? $path : base_path($path);
        return is_file($abs) ? $abs : null;
    }

    private function wrap(string $text, string $font, int $size, int $maxWidth): array
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = str_replace("\\n", "\n", $text);

        $paragraphs = explode("\n", $text);
        $lines = [];

        foreach ($paragraphs as $para) {
            $para = trim($para);

            if ($para === '') {
                $lines[] = ' ';
                continue;
            }

            $words = preg_split('/\s+/', $para);
            $line = '';

            foreach ($words as $w) {
                $try = trim($line . ' ' . $w);
                $bbox = imagettfbbox($size, 0, $font, $try);
                $width = $bbox[2] - $bbox[0];

                if ($width <= $maxWidth || $line === '') {
                    $line = $try;
                    continue;
                }

                $lines[] = $line;
                $line = $w;
            }

            if ($line !== '') {
                $lines[] = $line;
            }
        }

        return $lines;
    }

    private function fitText(string $text, string $font, int $max, int $min, int $maxWidth, int $maxLines): int {
        for ($s=$max; $s>=$min; $s-=2) {
            $lines = $this->wrap($text, $font, $s, $maxWidth);
            if (count($lines) <= $maxLines) {
                $ok = true;
                foreach ($lines as $ln) {
                    $bbox = imagettfbbox($s, 0, $font, $ln);
                    if (($bbox[2]-$bbox[0]) > $maxWidth) { $ok=false; break; }
                }
                if ($ok) return $s;
            }
        }
        return $min;
    }
}
