<?php
include 'lib/main.inc.php';

$uid = $_GET['id'] ?? $_GET['uid'] ?? false;

$w = 100;
$h = 148;
$payload = get_urls($uid);

$image_url = $payload['classic']['big'];

if (!$image_url) exit;
$name = $id ? $id : sha1(basename($image_url));

// Télécharger l'image dans un fichier temporaire
$image_path = tempnam(sys_get_temp_dir(), 'pdf_image') . '.jpg';
file_put_contents($image_path, file_get_contents($image_url));

// Créer une instance PDF
$pdf = new FPDF('P', 'mm', array($w, $h));

// Ajouter une page
$pdf->AddPage();

// Insérer l'image
// $pdf->Image($image_path, 5, 5, 90, 110.07);
$bordure = 2.8;
$largeur = 100;
$hauteur = 122.3;
$ratio = $hauteur / $largeur;

$largeurDef = $largeur - (2 * $bordure);
$hauteurDef = $largeurDef * $ratio;
$pdf->Image($image_path, $bordure, $bordure, 100 - 2 * $bordure, $hauteurDef);

// Supprimer le fichier temporaire
unlink($image_path);

// Sauvegarder le PDF
$pdf->Output('I', $name . '.pdf');
