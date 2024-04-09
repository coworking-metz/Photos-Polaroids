<?php

function svgToPng($svg, $replace=[]) {
    $tmpfile = tempnam(sys_get_temp_dir(), 'svg');

    $svgContent = file_get_contents($svg);
    foreach($replace as $k=>$v) {
        $svgContent = str_replace('{{'.$k.'}}', $v, $svgContent);
    }

    $image = new Imagick();
    $image->readImageBlob($svgContent);
    $image->transparentPaintImage("white", 0, 0, false);
    // $image->setImageAlphaChannel(Imagick::ALPHACHANNEL_ACTIVATE); 
    $image->setImageFormat("png32");
    $image->writeImage($tmpfile);

    
    return $tmpfile;
}


/**
 * Crée une ressource d'image à partir d'un fichier.
 *
 * @param string $filepath Chemin d'accès au fichier image.
 *
 * @return GDImage|resource|false Ressource d'image ou false en cas d'échec.
 */
function imagecreatefromfile($filepath)
{
    
    // Check if the file exists
    if (!file_exists($filepath)) {
        return false;
    }

    // Determine the type of image
    $type = exif_imagetype($filepath);
    switch ($type) {
        case IMAGETYPE_JPEG:
            return imagecreatefromjpeg($filepath);
        case IMAGETYPE_PNG:
            return imagecreatefrompng($filepath);
        case IMAGETYPE_WEBP:
            return imagecreatefromwebp($filepath);
            // Add more cases as needed, like for GIF, BMP, etc.
        default:
            return false; // Or throw an exception, based on your needs.
    }
}
