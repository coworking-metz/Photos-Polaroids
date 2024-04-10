<?php


/**
 * Outputs an image with the correct headers.
 * If the image is a PNG, it converts it to JPG before outputting.
 * Resizes the image to the given width while keeping the aspect ratio if width is set.
 * 
 * @param string $imagePath Path to the image file.
 * @param int|null $width Optional width to resize the image.
 * @param string $destinationPath Path where the  image will be written.
 */
function outputImageWithHeaders($imagePath, $width = null, $destinationPath = false)
{


    $extension = strtolower(@end(explode('.',basename($imagePath))));
    if ($extension === 'png') {
        $image = imagecreatefrompng($imagePath);
    } else {
        $image = imagecreatefromstring(file_get_contents($imagePath));
    }
    if ($width && imagesx($image) > $width) {
        $height = (int) (imagesy($image) * ($width / imagesx($image)));
        $resizedImage = imagescale($image, $width, $height);
        imagedestroy($image);
        $image = $resizedImage;
    }
    if ($extension === 'png') {
        header('Content-Type: image/jpeg');
        write_and_output_image($image, $destinationPath, 'jpg');
    } else {
        $mimeType = 'image/'.str_replace('jpg','jpeg',$extension);
        header("Content-Type: $mimeType");
        if ($mimeType === 'image/jpeg') {
            write_and_output_image($image, $destinationPath, 'jpg');
        } elseif ($mimeType === 'image/gif') {
            write_and_output_image($image, $destinationPath, 'gif');
        } elseif ($mimeType === 'image/png') {
            write_and_output_image($image, $destinationPath, 'png');
        }
    }
    cloudflareHit();
    imagedestroy($image);
    exit;
}


function write_and_output_image($image, $path, $type)
{
    if ($type == 'jpg') {
        imagejpeg($image);
        return $path ? imagejpeg($image, $path) : true;
    }
    if ($type == 'gif') {
        imagegif($image);
        return $path ? imagegif($image, $path) : true;
    }
    if ($type == 'png') {
        imagepng($image);
        return $path ? imagepng($image, $path) : true;
    }
}

function svgToPng($svg, $replace=[]) {
    $tmpfile = tempnam(sys_get_temp_dir(), 'svg');

    $svgContent = file_get_contents($svg);
    foreach($replace as $k=>$v) {
        $svgContent = str_replace('{{'.$k.'}}', $v, $svgContent);
    }

    $image = new Imagick();
    $image->readImageBlob($svgContent);
    // $image->transparentPaintImage("white", 0, 0, false);
    // $image->setImageAlphaChannel(Imagick::ALPHACHANNEL_ACTIVATE); 
    $image->setImageFormat("png64");
    $image->writeImage($tmpfile);

    
    return $tmpfile;
}


function remove_background($file) {
    $content = file_get_contents($file);

    $api = 'https://tools.sopress.net/remove-background/';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL,$api);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array('imageContent' => $content)));

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $server_output = curl_exec($ch);

    curl_close($ch);

    if($server_output) {
        file_put_contents($file, $server_output);
    }
    return $file;
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
