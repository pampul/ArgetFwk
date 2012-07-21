<?php

/* ------------------------------------

  Regroupement des fonctions communes PHP

  ------------------------------------ */




// Verifie la présence de http devant le lien
function checkhttp($lien) {

    if (!preg_match("#http://#", $lien)) {
        return "http://" . $lien;
    } else {
        return $lien;
    }
}

function recupereImageDuTexte($texte) {
    
    $resultat = null;

    if (preg_match("#<img(.*?)>#s", stripslashes($texte), $matches)) {
        preg_match('#src="((.*?)")#', $matches[0], $resultat);
        if ($resultat[0] == null) {
            preg_match("#src='((.*?)')#", $matches[0], $resultat);
            if (!preg_match("#src='/gestion/#", $resultat[0])) {
                $newSRC = str_replace("src='", "src='gestion/", $resultat[0]);
            } else {
                $newSRC = $resultat[0];
            }
        } else {
            if (!preg_match('#src="/gestion/#', $resultat[0])) {
                $newSRC = str_replace('src="', 'src="gestion/', $resultat[0]);
            } else {
                $newSRC = $resultat[0];
            }
        }
    } else {
        $newSRC = null;
    }

    if(checkhttp($newSRC)) return $resultat[0];
    else return $newSRC;
}


// Coupe du texte qui est trop long sans abimer la chaine
function couperTexte($longueurMax, $texte) {

    $texte = stripslashes($texte);
    $displayText = preg_replace("#&nbsp;#", "", strip_tags($texte));
    $var = " [...]";
    $length = strlen($displayText);
    $displayText = wordwrap($displayText, $longueurMax, ";-;-;");
    $tableau = explode(";-;-;", $displayText);
    if($length > $longueurMax) return $tableau[0].$var;
        else return $tableau[0];
}


/* Fonction qui r?cup?re la source d'une image dans une chaine de caract?re, et remplace par le second champ s'il ne trouve pas
  Elle supprime par la m?me occasion le 3eme champ dans la source si rempli (? ?diter)
  ------------------------------------------------------------------------------------------------------------------------------------------ */

function foundSrcImage($chaineCaractere, $srcRemplacement) {

    preg_match("#<img(.*?)>#s", $chaineCaractere, $matches);

    if (isset($matches[0])) {

        preg_match('#src="((.*?)")#', $matches[0], $resultat);

        if ($resultat[0] == null) {

            preg_match("#src='((.*?)')#", $matches[0], $resultat);

            if (!preg_match("#src='/back/#", $resultat[0])) {

                $newSRC = str_replace("src='", "src='back/", $resultat[0]);
            } else {

                $newSRC = $resultat[0];
            }
        } else {

            if (!preg_match('#src="/back/#', $resultat[0])) {

                $newSRC = str_replace('src="', 'src="back/', $resultat[0]);
            } else {

                $newSRC = $resultat[0];
            }
        }
    } else {
        $newSRC = $srcRemplacement;
    }
}

function paginationWithImage($select) {

    global $content;
    global $nombreDePages;
    global $pageActuelle;
    global $nbrLignes;

    if ($nombreDePages > 1) {
        echo '<div id="pagination"><p>Pages : ';
    } else {
        echo '<div id="pagination"><p>Page : ';
    }

    $check = 0;
    $passagePageActuelle = 0;
    $backpage = $pageActuelle - 1;
    $nextpage = $pageActuelle + 1;

    if ($nombreDePages > 1 && $pageActuelle > 1) {
        if ($select != null) {
            echo '<a href="?content=' . $content . '&page=' . $backpage . '' . $select . '" title="Aller &agrave; la page -' . $backpage . '-" class="precedant"></a> &nbsp;|';
        } else {
            echo '<a href="?content=' . $content . '&page=' . $backpage . '" title="Aller &agrave; la page -' . $backpage . '-" class="precedant"></a> &nbsp;|';
        }
    }

    for ($i = 1; $i <= $nombreDePages; $i++) {

        if ($i == $pageActuelle) {

            if ($pageActuelle == $nombreDePages) {
                echo ' <span style="color: #2e85d7;">[ ' . $i . ' ]</span>';
            } else {
                echo ' <span style="color: #2e85d7;">[ ' . $i . ' ]</span> -';
            }
            $passagePageActuelle = 1;
        } else {

            if ($backpage == $i) {

                if ($select != null) {
                    echo ' <a href="?content=' . $content . '&page=' . $i . '' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                } else {
                    echo ' <a href="?content=' . $content . '&page=' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                }
            } elseif ($nextpage == $i) {

                if ($nextpage == $nombreDePages) {

                    if ($select != null) {
                        echo ' <a href="?content=' . $content . '&page=' . $i . '' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a>';
                    } else {
                        echo ' <a href="?content=' . $content . '&page=' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a>';
                    }
                } else {

                    if ($select != null) {
                        echo ' <a href="?content=' . $content . '&page=' . $i . '' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                    } else {
                        echo ' <a href="?content=' . $content . '&page=' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                    }
                }
            } elseif ($i < 2) {

                if ($select != null) {
                    echo ' <a href="?content=' . $content . '&page=' . $i . '' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                } else {
                    echo ' <a href="?content=' . $content . '&page=' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                }
            } elseif ($i >= 2 && $nombreDePages == $i) {

                if ($select != null) {
                    echo ' <a href="?content=' . $content . '&page=' . $i . '' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a>';
                } else {
                    echo ' <a href="?content=' . $content . '&page=' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a>';
                }
            } elseif ($i >= 2 && $check == 0) {

                $check = $i;
                echo '... -';
            } elseif ($i >= 2 && $passagePageActuelle == 1 && $pageActuelle > $check) {

                $check = $pageActuelle + 1;
                echo '... -';
            } else {
                echo "";
            }
        }
    }
    if ($nombreDePages > 1 && $pageActuelle < $nombreDePages) {
        if ($select != null) {
            echo '&nbsp;| &nbsp;<a href="?content=' . $content . '&page=' . $nextpage . '' . $select . '" title="Aller &agrave; la page -' . $nextpage . '-"  class="suivant" ></a>';
        } else {
            echo '&nbsp;| &nbsp;<a href="?content=' . $content . '&page=' . $nextpage . '" title="Aller &agrave; la page -' . $nextpage . '-"  class="suivant" ></a>';
        }
    }
    echo '<i>&nbsp;&nbsp;&nbsp; (Total: ' . $nbrLignes . ') </i></p></div>';
}

function paginationImageAndUrlRewriting($select, $url) {

    global $content;
    global $nombreDePages;
    global $pageActuelle;
    global $nbrLignes;

    if ($nombreDePages > 1) {
        echo '<div id="pagination"><p>Pages : ';
    } else {
        echo '<div id="pagination"><p>Page : ';
    }

    $check = 0;
    $passagePageActuelle = 0;
    $backpage = $pageActuelle - 1;
    $nextpage = $pageActuelle + 1;

    if ($nombreDePages > 1 && $pageActuelle > 1) {
        if ($select != null) {
            echo '<a href="' . $url . $content . '/page-' . $backpage . '/' . $select . '" title="Aller &agrave; la page -' . $backpage . '-" class="precedant"></a> &nbsp;|';
        } else {
            echo '<a href="' . $url . $content . '/page-' . $backpage . '" title="Aller &agrave; la page -' . $backpage . '-" class="precedant"></a> &nbsp;|';
        }
    }

    for ($i = 1; $i <= $nombreDePages; $i++) {

        if ($i == $pageActuelle) {

            if ($pageActuelle == $nombreDePages) {
                echo ' <span style="color: #2e85d7;">[ ' . $i . ' ]</span>';
            } else {
                echo ' <span style="color: #2e85d7;">[ ' . $i . ' ]</span> -';
            }
            $passagePageActuelle = 1;
        } else {

            if ($backpage == $i) {

                if ($select != null) {
                    echo ' <a href="' . $url . $content . '/page-' . $i . '/' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                } else {
                    echo ' <a href="' . $url . $content . '/page-' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                }
            } elseif ($nextpage == $i) {

                if ($nextpage == $nombreDePages) {

                    if ($select != null) {
                        echo ' <a href="' . $url . $content . '/page-' . $i . '/' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a>';
                    } else {
                        echo ' <a href="' . $url . $content . '/page-' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a>';
                    }
                } else {

                    if ($select != null) {
                        echo ' <a href="' . $url . $content . '/page-' . $i . '/' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                    } else {
                        echo ' <a href="' . $url . $content . '/page-' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                    }
                }
            } elseif ($i < 2) {

                if ($select != null) {
                    echo ' <a href="' . $url . $content . '/page-' . $i . '/' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                } else {
                    echo ' <a href="' . $url . $content . '/page-' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a> - ';
                }
            } elseif ($i >= 2 && $nombreDePages == $i) {

                if ($select != null) {
                    echo ' <a href="' . $url . $content . '/page-' . $i . '/' . $select . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a>';
                } else {
                    echo ' <a href="' . $url . $content . '/page-' . $i . '" title="Aller &agrave; la page -' . $i . '-" >' . $i . '</a>';
                }
            } elseif ($i >= 2 && $check == 0) {

                $check = $i;
                echo '... -';
            } elseif ($i >= 2 && $passagePageActuelle == 1 && $pageActuelle > $check) {

                $check = $pageActuelle + 1;
                echo '... -';
            } else {
                echo "";
            }
        }
    }
    if ($nombreDePages > 1 && $pageActuelle < $nombreDePages) {
        if ($select != null) {
            echo '&nbsp;| &nbsp;<a href="' . $url . $content . '/page-' . $nextpage . '/' . $select . '" title="Aller &agrave; la page -' . $nextpage . '-"  class="suivant" ></a>';
        } else {
            echo '&nbsp;| &nbsp;<a href="' . $url . $content . '/page-' . $nextpage . '" title="Aller &agrave; la page -' . $nextpage . '-"  class="suivant" ></a>';
        }
    }
    echo '<i>&nbsp;&nbsp;&nbsp; (Total: ' . $nbrLignes . ') </i></p></div>';
}

?>