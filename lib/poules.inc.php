<?php


function nom_random() {
    $nom1 = ['Alice','Andrée','Anne','Béatrice','Bernadette','Brigitte','Caroline','Catherine','Chantal','Christiane','Clara','Colette','Colette','Danielle','Denise','Eliane','Émilie','Francoise','Genevieve','Georgette','Germaine','Gilberte','Ginette','Gisèle','Hélène','Henriette','Huguette','Irene','Isabelle','Isabelle','Jacqueline','Janine','Jeanine','Jeanne','Jeannine','Josette','Josette','Juliette','Liliane','Louise','Louise','Louisette','Lucette','Lucienne','Madeleine','Madeleine','Marcelle','Margot','Marguerite','Marie','Marthe','Mauricette','Micheline','Michelle','Monique','Monique','Nicole','Nicole','Odette','Olivia','Patricia','Paulette','Pauline','Pierrette','Raymonde','Renée','Simone','Simonne','Solange','Sophie','Suzanne','Suzanne','Sylvie','Therese','Valérie','Yvette','Yvonne'];
    
    $nom2 = ['Plume','Picore','Cocotte','Caquete','Poulette','Couvée','Nid','Éclosion','Volaille','Caille','Pondue','Muesli','Coquille','Oeuf','Paille','Bec','Poussine','Oeufine','Cotcot','Chicken','Caquet','Poulinette','Grattelle','Pioupiou','Poulette','Piaille','Picote','Becquée','Plume','Canarde'];


    return $nom1[array_rand($nom1)].'-'.$nom2[array_rand($nom2)];
    
}
