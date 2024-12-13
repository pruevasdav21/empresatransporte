<?php
/*
 * PHP QR Code encoder
 *
 * Image output of code using GD2
 *
 * PHP QR Code is distributed under LGPL 3
 * Copyright (C) 2010 Dominik Dzienia <deltalab at poczta dot fm>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 3 of the License, or any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA
 */

define('QR_IMAGE', true);

class QRimage
{
    //--------------------------------------------------------------------------
    public static function png($frame, $filename = false, $pixelPerPoint = 4, $outerFrame = 4, $saveAndPrint = false)
    {
        $image = self::createImage($frame, $pixelPerPoint, $outerFrame);
        
        if ($filename === false) {
            header("Content-type: image/png");
            imagepng($image);
        } else {
            imagepng($image, $filename);
            if ($saveAndPrint === true) {
                header("Content-type: image/png");
                imagepng($image);
            }
        }

        imagedestroy($image);
    }

    //--------------------------------------------------------------------------
    public static function jpg($frame, $filename = false, $pixelPerPoint = 8, $outerFrame = 4, $quality = 85)
    {
        $image = self::createImage($frame, $pixelPerPoint, $outerFrame);
        
        if ($filename === false) {
            header("Content-type: image/jpeg");
            imagejpeg($image, null, $quality);
        } else {
            imagejpeg($image, $filename, $quality);
        }

        imagedestroy($image);
    }

    //--------------------------------------------------------------------------
    private static function createImage($frame, $pixelPerPoint = 4, $outerFrame = 4)
    {
        $height = count($frame);
        $width = strlen($frame[0]);
        
        $imgWidth = $width + 2 * $outerFrame;
        $imgHeight = $height + 2 * $outerFrame;
        
        $baseImage = imagecreate($imgWidth, $imgHeight);
        
        $colors[0] = imagecolorallocate($baseImage, 255, 255, 255); // White
        $colors[1] = imagecolorallocate($baseImage, 0, 0, 0);       // Black

        imagefill($baseImage, 0, 0, $colors[0]);

        for ($y = 0; $y < $height; $y++) {
            for ($x = 0; $x < $width; $x++) {
                if ($frame[$y][$x] === '1') {
                    imagesetpixel($baseImage, $x + $outerFrame, $y + $outerFrame, $colors[1]);
                }
            }
        }
        
        $targetImage = imagecreate($imgWidth * $pixelPerPoint, $imgHeight * $pixelPerPoint);
        imagecopyresized(
            $targetImage,
            $baseImage,
            0,
            0,
            0,
            0,
            $imgWidth * $pixelPerPoint,
            $imgHeight * $pixelPerPoint,
            $imgWidth,
            $imgHeight
        );

        imagedestroy($baseImage);

        return $targetImage;
    }
}
?>
