<?php
function get_urls($uid) {
    $data = get_polaroid_data($uid);

    $sizes = ['micro','small','medium','big'];
    $payload = ['pdf'=>URL_SITE.''.$uid.'.pdf'];
    foreach(['photo', 'polaroid'] as $cle) {
        $payload[$cle] = [];
        foreach($sizes as $size) {
            $payload[$cle][$size]=URL_SITE.$cle.'/size/'.$size.'/'.$uid.'.jpg';
        }
    }
    $payload['anonyme'] = [];
    foreach($sizes as $size) {
        $payload['anonyme'][$size]=URL_SITE.'polaroid/size/'.$size.'/anonyme/'.$uid.'.jpg';
    }
    $payload['classic'] = [];
    foreach($sizes as $size) {
        $payload['classic'][$size]=URL_SITE.'polaroid/size/'.$size.'/classic/'.$uid.'.jpg';
    }

    return $payload;
}

function get_polaroid_data($uid)
{
    $api =
        "https://wpapi.coworking-metz.fr/api-json-wp/cowo/v1/polaroid/" . $uid;

    $content = file_get_contents($api);

    if (!$content) {
        return;
    }

    return json_decode($content, true);
}

function generer_polaroid($data, $params = [])
{
    $options = $data["options"] ?? [];
    $pola_source = url_to_file($options["cadre"]);
    $image_fond_pola = url_to_file($options["image_fond_pola"] ?? false);

    $quality = $params["quality"] ?? 90;
    $size = $params["size"] ?? false;
    $anonyme = $params["anonyme"] ?? false;
    $classic = $params["classic"] ?? false;
    if($anonyme) {
        $data['nom']=nom_random(); 
        if($data['visite']) {
            $data['description'] = 'Visite & Journée d\'éssai';
        } else {
            $data["photo"] = $options['photo_par_defaut'];
            $data["alpha"] = $options['photo_par_defaut_alpha'];
            $data['description'] = 'Adhérente du Poulailler';
        }
    }
    if ($data["legacy"]) {
        $img = imagecreatefromfile(url_to_file($data["legacy"]));

        $newWidth = false;
        if ($size == "micro") {
            $newWidth = 20;
        } elseif ($size == "small") {
            $newWidth = 200;
        } else {
            $newWidth = 400;
        }
        if ($newWidth) {
            // Obtient les dimensions originales de l'image
            $originalWidth = imagesx($img);
            $originalHeight = imagesy($img);

            // Calcule la nouvelle hauteur tout en conservant le ratio d'aspect
            $newHeight = ($newWidth / $originalWidth) * $originalHeight;

            // Crée une nouvelle image avec les nouvelles dimensions
            $resizedImage = imagecreatetruecolor($newWidth, $newHeight);

            // Redimensionne l'image originale dans la nouvelle image
            imagecopyresampled(
                $resizedImage,
                $img,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $originalWidth,
                $originalHeight
            );

            $img = $resizedImage;
        }
    } else {
        if (!$classic && $image_fond_pola) {
            $photo = $data["alpha"];
        } else {
            $photo = $data["photo"];
        }
        $photo = url_to_file($photo);

        list($width, $height) = getimagesize($pola_source);
        $img = imagecreatetruecolor($width, $height);

        $bande = intval(($height * 5.3) / 100);
        $demie_bande = ceil($bande/2);
        $frameRatio = 1069 / 1032;
        $frameWidth = $width - 2 * $bande;
        $frameHeight = intval($frameWidth * $frameRatio);

        // Code to overlay $image_fond_pola onto $img
        if ($image_fond_pola) {
            $fond = imagecreatefromfile($image_fond_pola);

            // Obtient les dimensions de $image_fond_pola
            list($polaWidth, $polaHeight) = getimagesize($image_fond_pola);

            // Calcule les nouvelles dimensions tout en gardant le ratio
            $newHeight = ($width / $polaWidth) * $polaHeight;

            // Redimensionne $image_fond_pola
            $resizedPola = imagecreatetruecolor($width, $newHeight);
            imagecopyresampled(
                $resizedPola,
                $fond,
                0,
                0,
                0,
                0,
                $width,
                $newHeight,
                $polaWidth,
                $polaHeight
            );

            // Place $resizedPola en haut à gauche de $img
            imagecopy($img, $resizedPola, 0, 0, 0, 0, $width, $newHeight);

            // Libère la mémoire
            imagedestroy($resizedPola);
        }

        /**
         * Ajout de la photo du coworker
         */
        $tmp = imagecreatefromfile($photo);
        list($tmpWidth, $tmpHeight) = getimagesize($photo);

        $mode = $tmpWidth - $tmpHeight > 100 ? "landscape" : "portrait";
        $aspectRatio = $tmpWidth / $tmpHeight;

        if ($mode == "landscape") {
            $newHeight = ($height * 75) / 100;
            $newWidth = $newHeight * $aspectRatio;
            if ($newWidth < $frameWidth) {
                $newWidth = $frameWidth;
                $newHeight = $newWidth * $aspectRatio;
            }
        } else {
            $newWidth = $frameWidth;
            $newHeight = $newWidth / $aspectRatio;
            if ($newHeight < $frameHeight) {
                $newHeight = $frameHeight;
                $newWidth = $newHeight * $aspectRatio;
            }
        }

        // print_r([$newWidth, $newHeight, $tmpWidth, $tmpHeight]);exit;
        // if ($newHeight > $height) {
        //     $newHeight = $height;
        //     $newWidth = $height * $aspectRatio;
        // }

        @imagecopyresampled(
            $img,
            $tmp,
            $bande,
            $bande + 2,
            0,
            0,
            $newWidth,
            $newHeight,
            $tmpWidth,
            $tmpHeight
        );
        imagedestroy($tmp);

        /**
         * AJout du cadre du pola vide au dessus de la photo
         */
        // 4. Open the './images/pola-vide.png' file and place it on top of everything in $img
        $overlay = imagecreatefrompng($pola_source);
        imagecopy($img, $overlay, 0, 0, 0, 0, $width, $height);
        imagedestroy($overlay);

        // Text to be added
        $text = stripslashes($data["nom"]);
        $fontFile = CHEMIN_FONTS . "/EvelethClean.ttf"; // This is the path to your font file
        $fontSize = 40; // This is the font size, adjust as needed
        $fontColor = imagecolorallocate($img, 0, 0, 0); // Black color for the font

        // Get bounding box of the text
        $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
        $textWidth = $textBox[2] - $textBox[0];
        $textHeight = $textBox[1] - $textBox[7];
        // Calculate coordinates
        $x = $width / 2 - $textWidth / 2;
        $y = $height * 0.89 - $textHeight / 2;

        // Add the text to the image
        @imagettftext($img, $fontSize, 0, $x, $y, $fontColor, $fontFile, $text);
        $description = $data["description"]??'';
        $complement = $data["complement"]??'';
        if ($description && $complement) {
            $fontSize = 35;
            $line = 0.94;
        } else {
            $fontSize = 40;
            $line = 0.96;
        }
        $fontFile = CHEMIN_FONTS . "/EvelethCleanThin.ttf";

        if ($text = stripslashes($description)) {
            // Get bounding box of the text
            $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            // Calculate coordinates
            $x = $width / 2 - $textWidth / 2;
            $y = $height * $line - $textHeight / 2;

            @imagettftext(
                $img,
                $fontSize,
                0,
                $x,
                $y,
                $fontColor,
                $fontFile,
                $text
            );
        }

        if ($text = stripslashes($complement)) {
            // Get bounding box of the text
            $textBox = imagettfbbox($fontSize, 0, $fontFile, $text);
            $textWidth = $textBox[2] - $textBox[0];
            $textHeight = $textBox[1] - $textBox[7];
            // Calculate coordinates
            $x = $width / 2 - $textWidth / 2;
            $y = $height * ($line + 0.035) - $textHeight / 2;

            imagettftext(
                $img,
                $fontSize,
                0,
                $x,
                $y,
                $fontColor,
                $fontFile,
                $text
            );
        }

            // Get the current width and height of the image
            $originalWidth = imagesx($img);
            $originalHeight = imagesy($img);

        $medaillePath = get_medaille($data['ranking']);
        if(!$classic && $medaillePath) {
            // file_put_contents(CHEMIN_SITE.'test.png', file_get_contents($medaillePath));
            // echo '<div style="background:red" ><img src="/test.png"></div>';exit;
            
            // header('Content-type: image/png');
            // readfile($medaillePath);
            // exit;

            $medaille = imagecreatefrompng($medaillePath);

            $largeurMedaille = imagesx($medaille);
            $hauteurMedaille = imagesy($medaille);

            // // Calculer les nouvelles dimensions de la médaille pour être 20% de la largeur de $img, en conservant le ratio d'aspect
            // $nouvelleLargeur = intval($originalWidth * 0.2);
            // $ratio = $nouvelleLargeur / $largeurMedaille;
            // $nouvelleHauteur = intval($hauteurMedaille * $ratio);
            // if($nouvelleLargeur < $largeurMedaille) {
            //     // Redimensionner l'image de la médaille
            //     $medailleRedimensionnee = imagecreatetruecolor($nouvelleLargeur, $nouvelleHauteur);
            //     imagecopyresampled($medailleRedimensionnee, $medaille, 0, 0, 0, 0, $nouvelleLargeur, $nouvelleHauteur, $largeurMedaille, $hauteurMedaille);
            //     $medaille = $medailleRedimensionnee;
            //     $largeurMedaille = $nouvelleLargeur;
            //     $hauteurMedaille = $nouvelleHauteur;
            // }
            
            // Calculer la position de la médaille pour la placer dans le coin inférieur droit
            $x = $originalWidth - $largeurMedaille - $demie_bande;
            $y = $frameHeight - $bande;
            // Placer l'image de la médaille sur l'image principale
            imagecopy($img, $medaille, $x, $y, 0, 0, $largeurMedaille, $hauteurMedaille);

        }
        
        if ($size != "big") {

            if ($size == "micro") {
                $newWidth = 20;
            } elseif ($size == "small") {
                $newWidth = 200;
            } else {
                $newWidth = 400;
            }

            $aspectRatio = $originalWidth / $originalHeight;
            $newHeight = intval($newWidth / $aspectRatio);

            // Create a new blank image with the calculated width and height
            $newImage = imagecreatetruecolor($newWidth, $newHeight);

            // Resample the original image onto the new image
            imagecopyresampled(
                $newImage,
                $img,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $originalWidth,
                $originalHeight
            );

            // Output or save the new ima
            $img = $newImage;
        }
    }
    cacheHeaders();
    // 5. Output the image as jpeg
    header("Content-Type: image/jpeg");
    imagejpeg($img, null, $quality);

    imagedestroy($img);
}
