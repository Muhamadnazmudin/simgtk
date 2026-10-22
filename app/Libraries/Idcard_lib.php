<?php

namespace App\Libraries;

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class Idcard_lib
{
    protected $db;

    /*
     * Rasio KTP / CR80
     * 85.60 : 53.98
     *
     * 1012 x 638 = rasio ± 1.586
     */
    protected $W = 1012;
    protected $H = 638;


    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }


    /* =========================================================
       ROUNDED RECTANGLE
       ========================================================= */

    private function drawRoundedRect(
        $img,
        $x1,
        $y1,
        $x2,
        $y2,
        $radius,
        $color
    ) {
        imagefilledrectangle(
            $img,
            $x1 + $radius,
            $y1,
            $x2 - $radius,
            $y2,
            $color
        );

        imagefilledrectangle(
            $img,
            $x1,
            $y1 + $radius,
            $x2,
            $y2 - $radius,
            $color
        );

        imagefilledellipse(
            $img,
            $x1 + $radius,
            $y1 + $radius,
            $radius * 2,
            $radius * 2,
            $color
        );

        imagefilledellipse(
            $img,
            $x2 - $radius,
            $y1 + $radius,
            $radius * 2,
            $radius * 2,
            $color
        );

        imagefilledellipse(
            $img,
            $x1 + $radius,
            $y2 - $radius,
            $radius * 2,
            $radius * 2,
            $color
        );

        imagefilledellipse(
            $img,
            $x2 - $radius,
            $y2 - $radius,
            $radius * 2,
            $radius * 2,
            $color
        );
    }


    /* =========================================================
       CENTER TEXT
       ========================================================= */

    private function centerX(
        $width,
        $fontSize,
        $font,
        $text
    ) {
        $box = imagettfbbox(
            $fontSize,
            0,
            $font,
            $text
        );

        $textWidth = abs(
            $box[2] - $box[0]
        );

        return ($width - $textWidth) / 2;
    }


    /* =========================================================
       AUTO FIT TEXT
       ========================================================= */

    private function autoFitText(
        $maxWidth,
        $font,
        $text,
        $maxSize,
        $minSize
    ) {
        for (
            $size = $maxSize;
            $size >= $minSize;
            $size--
        ) {
            $box = imagettfbbox(
                $size,
                0,
                $font,
                $text
            );

            $width = abs(
                $box[2] - $box[0]
            );

            if ($width <= $maxWidth) {
                return $size;
            }
        }

        return $minSize;
    }


    /* =========================================================
       LOAD IMAGE
       JPG / PNG / WEBP
       ========================================================= */

    private function loadImage($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        $info = @getimagesize($file);

        if (!$info) {
            return false;
        }

        switch ($info['mime']) {

            case 'image/jpeg':

                return @imagecreatefromjpeg($file);

            case 'image/png':

                return @imagecreatefrompng($file);

            case 'image/webp':

                if (
                    function_exists(
                        'imagecreatefromwebp'
                    )
                ) {
                    return @imagecreatefromwebp($file);
                }

                break;
        }

        return false;
    }


    /* =========================================================
       CREATE CROPPED PHOTO
       ========================================================= */

    private function createPhoto(
        $file,
        $width,
        $height
    ) {
        $source = $this->loadImage($file);

        if (!$source) {
            return false;
        }

        $srcW = imagesx($source);
        $srcH = imagesy($source);

        $srcRatio = $srcW / $srcH;
        $dstRatio = $width / $height;

        if ($srcRatio > $dstRatio) {

            /*
             * Foto terlalu lebar
             */

            $cropH = $srcH;
            $cropW = (int) (
                $srcH * $dstRatio
            );

            $srcX = (int) (
                ($srcW - $cropW) / 2
            );

            $srcY = 0;

        } else {

            /*
             * Foto terlalu tinggi
             */

            $cropW = $srcW;
            $cropH = (int) (
                $srcW / $dstRatio
            );

            $srcX = 0;

            $srcY = (int) (
                ($srcH - $cropH) / 2
            );
        }

        $photo = imagecreatetruecolor(
            $width,
            $height
        );

        imagecopyresampled(
            $photo,
            $source,
            0,
            0,
            $srcX,
            $srcY,
            $width,
            $height,
            $cropW,
            $cropH
        );

        imagedestroy($source);

        return $photo;
    }


    /* =========================================================
       GENERATE
       ========================================================= */

    public function generate($id)
    {
        /*
         * =====================================================
         * DATA SISWA
         * =====================================================
         */

        $siswa = $this->db->table('siswa')
            ->select('siswa.*, kelas.nama AS nama_kelas')
            ->join('kelas', 'kelas.id = siswa.id_kelas', 'left')
            ->where('siswa.id', $id)
            ->get()
            ->getRow();

        if (!$siswa) {
            return null;
        }

        $qrFolder = FCPATH . 'assets/qrcodes/';
        if (!is_dir($qrFolder)) {
            mkdir($qrFolder, 0777, true);
        }

        $nisn = trim((string) $siswa->nisn);
        if ($nisn === '') {
            return null;
        }

        $qrFile = $qrFolder . $nisn . '.png';
        if (!file_exists($qrFile)) {
            try {
                $options = new QROptions([
                    'outputType'  => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel'    => QRCode::ECC_H,
                    'scale'       => 6,
                    'imageBase64' => false,
                ]);
                (new QRCode($options))->render($nisn, $qrFile);
            } catch (\Throwable $e) {
                log_message('error', 'ID Card QR generation failed: ' . $e->getMessage());
            }
        }


        /*
         * =====================================================
         * CANVAS
         * =====================================================
         */

        $W = $this->W;
        $H = $this->H;


        $img = imagecreatetruecolor(
            $W,
            $H
        );


        imagealphablending(
            $img,
            true
        );


        imagesavealpha(
            $img,
            true
        );


        /*
         * =====================================================
         * COLORS
         * =====================================================
         */

        $blueDark =
            imagecolorallocate(
                $img,
                7,
                82,
                190
            );


        $blue =
            imagecolorallocate(
                $img,
                20,
                119,
                232
            );


        $blueLight =
            imagecolorallocate(
                $img,
                76,
                177,
                248
            );


        $white =
            imagecolorallocate(
                $img,
                255,
                255,
                255
            );


        $whiteSoft =
            imagecolorallocatealpha(
                $img,
                255,
                255,
                255,
                45
            );


        $dark =
            imagecolorallocate(
                $img,
                25,
                38,
                55
            );


        $gray =
            imagecolorallocate(
                $img,
                100,
                116,
                135
            );


        $yellow =
            imagecolorallocate(
                $img,
                255,
                204,
                55
            );


        /*
         * =====================================================
         * FONT
         * =====================================================
         */

        $fontBold =
            FCPATH .
            'assets/fonts/Roboto-Bold.ttf';


        $fontRegular =
            FCPATH .
            'assets/fonts/Roboto-Regular.ttf';


        if (!file_exists($fontRegular)) {
            $fontRegular = $fontBold;
        }


        /*
         * =====================================================
         * BACKGROUND
         * =====================================================
         */

        imagefilledrectangle(
            $img,
            0,
            0,
            $W,
            $H,
            $blue
        );


        /*
         * Header
         */

        imagefilledrectangle(
            $img,
            0,
            0,
            $W,
            145,
            $blueDark
        );


        /*
         * Decorative circle kanan atas
         */

        imagefilledellipse(
            $img,
            $W + 30,
            -20,
            390,
            390,
            $blueLight
        );


        /*
         * Decorative circle kiri bawah
         */

        imagefilledellipse(
            $img,
            -60,
            $H + 50,
            270,
            270,
            $blueDark
        );


        /*
         * Ornamen kecil
         */

        imagefilledellipse(
            $img,
            850,
            535,
            18,
            18,
            $yellow
        );


        imagefilledellipse(
            $img,
            890,
            500,
            9,
            9,
            $yellow
        );


        /*
         * =====================================================
         * LOGO
         * =====================================================
         */

        $logo =
            FCPATH .
            'assets/img/logobonti.png';


        $logoX = 30;
        $logoY = 22;
        $logoSize = 92;


        if (file_exists($logo)) {

            $logoImg =
                $this->loadImage(
                    $logo
                );


            if ($logoImg) {

                /*
                 * Background logo
                 */

                $logoBg =
                    imagecolorallocatealpha(
                        $img,
                        255,
                        255,
                        255,
                        100
                    );


                imagefilledellipse(
                    $img,
                    $logoX + ($logoSize / 2),
                    $logoY + ($logoSize / 2),
                    $logoSize + 8,
                    $logoSize + 8,
                    $logoBg
                );


                imagecopyresampled(
                    $img,
                    $logoImg,
                    $logoX,
                    $logoY,
                    0,
                    0,
                    $logoSize,
                    $logoSize,
                    imagesx($logoImg),
                    imagesy($logoImg)
                );


                imagedestroy(
                    $logoImg
                );
            }
        }


        /*
         * =====================================================
         * HEADER TEXT
         * =====================================================
         */

        $textX = 145;


        /*
         * Baris 1
         */

        imagettftext(
            $img,
            16,
            0,
            $textX,
            48,
            $white,
            $fontBold,
            'SISTEM INFORMASI AKADEMIK SEKOLAH'
        );


        /*
         * Baris 2
         */

        imagettftext(
            $img,
            26,
            0,
            $textX,
            82,
            $white,
            $fontBold,
            'SMK NEGERI 1 CILIMUS'
        );


        /*
         * Baris 3
         */

        imagettftext(
            $img,
            9,
            0,
            $textX,
            108,
            $whiteSoft,
            $fontRegular,
            'KARTU IDENTITAS PESERTA DIDIK'
        );


        /*
         * =====================================================
         * STATUS
         * =====================================================
         */

        $statusW = 125;
        $statusH = 36;

        $statusX =
            $W -
            $statusW -
            28;

        $statusY = 28;


        $statusBg =
            imagecolorallocatealpha(
                $img,
                255,
                255,
                255,
                100
            );


        $this->drawRoundedRect(
            $img,
            $statusX,
            $statusY,
            $statusX + $statusW,
            $statusY + $statusH,
            18,
            $statusBg
        );


        $status =
            'SISWA AKTIF';


        $statusSize = 10;


        $statusBox =
            imagettfbbox(
                $statusSize,
                0,
                $fontBold,
                $status
            );


        $statusTextW =
            abs(
                $statusBox[2] -
                $statusBox[0]
            );


        imagettftext(
            $img,
            $statusSize,
            0,
            $statusX +
                (($statusW - $statusTextW) / 2),
            $statusY + 23,
            $white,
            $fontBold,
            $status
        );


        /*
         * =====================================================
         * BODY PANEL
         * =====================================================
         */

        $panelX = 25;
        $panelY = 163;
        $panelW = $W - 50;
        $panelH = 350;


        $panel =
            imagecolorallocatealpha(
                $img,
                255,
                255,
                255,
                8
            );


        $this->drawRoundedRect(
            $img,
            $panelX,
            $panelY,
            $panelX + $panelW,
            $panelY + $panelH,
            25,
            $panel
        );


        /*
         * =====================================================
         * FOTO
         * =====================================================
         */

        $photoX = 48;
        $photoY = 190;

        $photoW = 190;
        $photoH = 245;


        /*
         * Border putih
         */

        $photoBorder =
            imagecolorallocate(
                $img,
                255,
                255,
                255
            );


        $this->drawRoundedRect(
            $img,
            $photoX - 5,
            $photoY - 5,
            $photoX + $photoW + 5,
            $photoY + $photoH + 5,
            20,
            $photoBorder
        );


        /*
         * Cari foto
         */

        $photo = null;


        if (!empty($siswa->foto)) {

            $foto1 =
                FCPATH .
                'uploads/foto/' .
                $siswa->foto;


            $foto2 =
                FCPATH .
                'uploads/siswa/' .
                $siswa->foto;


            if (file_exists($foto1)) {

                $photo = $foto1;

            } elseif (file_exists($foto2)) {

                $photo = $foto2;
            }
        }


        if ($photo) {

            $photoImg =
                $this->createPhoto(
                    $photo,
                    $photoW,
                    $photoH
                );


            if ($photoImg) {

                /*
                 * Rounded photo
                 */

                $rounded =
                    imagecreatetruecolor(
                        $photoW,
                        $photoH
                    );


                imagealphablending(
                    $rounded,
                    false
                );


                imagesavealpha(
                    $rounded,
                    true
                );


                $transparent =
                    imagecolorallocatealpha(
                        $rounded,
                        0,
                        0,
                        0,
                        127
                    );


                imagefill(
                    $rounded,
                    0,
                    0,
                    $transparent
                );


                /*
                 * Copy photo
                 */

                imagecopy(
                    $rounded,
                    $photoImg,
                    0,
                    0,
                    0,
                    0,
                    $photoW,
                    $photoH
                );


                /*
                 * Mask rounded
                 */

                $mask =
                    imagecreatetruecolor(
                        $photoW,
                        $photoH
                    );


                imagealphablending(
                    $mask,
                    false
                );


                imagesavealpha(
                    $mask,
                    true
                );


                $transparentMask =
                    imagecolorallocatealpha(
                        $mask,
                        0,
                        0,
                        0,
                        127
                    );


                $whiteMask =
                    imagecolorallocatealpha(
                        $mask,
                        255,
                        255,
                        255,
                        0
                    );


                imagefill(
                    $mask,
                    0,
                    0,
                    $transparentMask
                );


                $radius = 18;


                imagefilledrectangle(
                    $mask,
                    $radius,
                    0,
                    $photoW - $radius,
                    $photoH,
                    $whiteMask
                );


                imagefilledrectangle(
                    $mask,
                    0,
                    $radius,
                    $photoW,
                    $photoH - $radius,
                    $whiteMask
                );


                imagefilledellipse(
                    $mask,
                    $radius,
                    $radius,
                    $radius * 2,
                    $radius * 2,
                    $whiteMask
                );


                imagefilledellipse(
                    $mask,
                    $photoW - $radius,
                    $radius,
                    $radius * 2,
                    $radius * 2,
                    $whiteMask
                );


                imagefilledellipse(
                    $mask,
                    $radius,
                    $photoH - $radius,
                    $radius * 2,
                    $radius * 2,
                    $whiteMask
                );


                imagefilledellipse(
                    $mask,
                    $photoW - $radius,
                    $photoH - $radius,
                    $radius * 2,
                    $radius * 2,
                    $whiteMask
                );


                /*
                 * Apply mask
                 */

                for (
                    $px = 0;
                    $px < $photoW;
                    $px++
                ) {

                    for (
                        $py = 0;
                        $py < $photoH;
                        $py++
                    ) {

                        $maskColor =
                            imagecolorat(
                                $mask,
                                $px,
                                $py
                            );


                        $alpha =
                            ($maskColor >> 24) & 0x7F;


                        $color =
                            imagecolorat(
                                $rounded,
                                $px,
                                $py
                            );


                        $r =
                            ($color >> 16) & 0xFF;

                        $g =
                            ($color >> 8) & 0xFF;

                        $b =
                            $color & 0xFF;


                        imagesetpixel(
                            $rounded,
                            $px,
                            $py,
                            imagecolorallocatealpha(
                                $rounded,
                                $r,
                                $g,
                                $b,
                                $alpha
                            )
                        );
                    }
                }


                imagecopy(
                    $img,
                    $rounded,
                    $photoX,
                    $photoY,
                    0,
                    0,
                    $photoW,
                    $photoH
                );


                imagedestroy(
                    $rounded
                );

                imagedestroy(
                    $mask
                );

                imagedestroy(
                    $photoImg
                );
            }

        } else {

            /*
             * INISIAL
             */

            $parts =
                preg_split(
                    '/\s+/',
                    strtoupper(
                        trim(
                            $siswa->nama
                        )
                    )
                );


            $initial =
                substr(
                    $parts[0] ?? 'S',
                    0,
                    1
                );


            if (count($parts) > 1) {

                $initial .=
                    substr(
                        end($parts),
                        0,
                        1
                    );
            }


            $initialSize = 70;


            $initialBox =
                imagettfbbox(
                    $initialSize,
                    0,
                    $fontBold,
                    $initial
                );


            $initialW =
                abs(
                    $initialBox[2] -
                    $initialBox[0]
                );


            $initialX =
                $photoX +
                (($photoW - $initialW) / 2);


            imagettftext(
                $img,
                $initialSize,
                0,
                $initialX,
                $photoY + 145,
                $blue,
                $fontBold,
                $initial
            );
        }


        /*
         * =====================================================
         * DATA SISWA
         * =====================================================
         */

        $dataX = 270;
        $dataY = 205;


        /*
         * Label section
         */

        imagettftext(
            $img,
            11,
            0,
            $dataX,
            $dataY,
            $blueDark,
            $fontBold,
            'DATA SISWA'
        );


        /*
         * Garis kecil
         */

        imageline(
            $img,
            $dataX,
            $dataY + 12,
            $dataX + 310,
            $dataY + 12,
            $blueLight
        );


        /*
         * Nama
         */

        $nama =
            strtoupper(
                trim(
                    $siswa->nama
                )
            );


        $namaSize =
            $this->autoFitText(
                315,
                $fontBold,
                $nama,
                30,
                18
            );


        imagettftext(
            $img,
            $namaSize,
            0,
            $dataX,
            $dataY + 55,
            $dark,
            $fontBold,
            $nama
        );


        /*
         * Detail
         */

        $detailY =
            $dataY + 100;


        /*
         * NISN
         */

        imagettftext(
            $img,
            10,
            0,
            $dataX,
            $detailY,
            $gray,
            $fontRegular,
            'NISN'
        );


        imagettftext(
            $img,
            10,
            0,
            $dataX + 70,
            $detailY,
            $dark,
            $fontBold,
            ': ' . ($siswa->nisn ?: '-')
        );


        /*
         * NIS
         */

        imagettftext(
            $img,
            10,
            0,
            $dataX,
            $detailY + 30,
            $gray,
            $fontRegular,
            'NIS'
        );


        imagettftext(
            $img,
            10,
            0,
            $dataX + 70,
            $detailY + 30,
            $dark,
            $fontBold,
            ': ' . ($siswa->nis ?: '-')
        );


        /*
         * Kelas
         */

        $kelas =
            isset($siswa->nama_kelas)
                ? $siswa->nama_kelas
                : '-';


        imagettftext(
            $img,
            10,
            0,
            $dataX,
            $detailY + 60,
            $gray,
            $fontRegular,
            'KELAS'
        );


        imagettftext(
            $img,
            10,
            0,
            $dataX + 70,
            $detailY + 60,
            $dark,
            $fontBold,
            ': ' . $kelas
        );


        /*
         * =====================================================
         * QR
         * =====================================================
         */

        $qrX = 670;
        $qrY = 205;
        $qrBox = 170;


        /*
         * QR white container
         */

        $qrWhite =
            imagecolorallocate(
                $img,
                255,
                255,
                255
            );


        $this->drawRoundedRect(
            $img,
            $qrX,
            $qrY,
            $qrX + $qrBox,
            $qrY + $qrBox,
            18,
            $qrWhite
        );


        /*
         * Load QR
         */

        $qr =
            $this->loadImage(
                $qrFile
            );


        if ($qr) {

            $padding = 13;


            imagecopyresampled(
                $img,
                $qr,
                $qrX + $padding,
                $qrY + $padding,
                0,
                0,
                $qrBox - ($padding * 2),
                $qrBox - ($padding * 2),
                imagesx($qr),
                imagesy($qr)
            );


            imagedestroy(
                $qr
            );
        }


        /*
         * QR LABEL
         */

        $qrLabel =
            'SCAN UNTUK IZIN KELUAR';


        $qrFontSize = 9;


        $qrBoxText =
            imagettfbbox(
                $qrFontSize,
                0,
                $fontBold,
                $qrLabel
            );


        $qrTextWidth =
            abs(
                $qrBoxText[2] -
                $qrBoxText[0]
            );


        imagettftext(
            $img,
            $qrFontSize,
            0,
            $qrX +
                (($qrBox - $qrTextWidth) / 2),
            $qrY + $qrBox + 21,
            $white,
            $fontBold,
            $qrLabel
        );


        /*
         * =====================================================
         * FOOTER
         * =====================================================
         */

        $footerY = 570;


        imageline(
            $img,
            35,
            $footerY - 16,
            $W - 35,
            $footerY - 16,
            $whiteSoft
        );


        imagettftext(
            $img,
            8,
            0,
            35,
            $footerY,
            $whiteSoft,
            $fontRegular,
            'Kartu identitas resmi peserta didik'
        );


        $footer =
            'ID SISWA';


        $footerSize = 8;


        $footerBox =
            imagettfbbox(
                $footerSize,
                0,
                $fontBold,
                $footer
            );


        $footerWidth =
            abs(
                $footerBox[2] -
                $footerBox[0]
            );


        imagettftext(
            $img,
            $footerSize,
            0,
            $W - 35 - $footerWidth,
            $footerY,
            $whiteSoft,
            $fontBold,
            $footer
        );


        /*
         * =====================================================
         * OUTPUT PNG BINARY
         * =====================================================
         */

        ob_start();

        imagepng(
            $img,
            null,
            9
        );

        $binary =
            ob_get_clean();


        imagedestroy(
            $img
        );


        return $binary;
    }
}