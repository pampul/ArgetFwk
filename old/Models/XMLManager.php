<?php

class XMLManager {

    public function updateXMLSequentiel($toutesLesAnnonces) {

        $src = "../flux.xml";

        if (file_exists($src)) {
            unlink($src);
        }

        $xml = "<?xml version='1.0' encoding='utf-8'?>
    <rss version='2.0' xmlns:g=\"http://base.google.com/ns/1.0\">
        <channel>
	    
            <title>Let's Sleep Watch</title>
            <link>http://www.letssleep.argetweb.fr/</link>
            <description>Site Web de Let's Sleep - La nouvelle montre revolutionnaire !</description>";

        $row = 0;
        $fichier = fopen($src, "w");
        foreach ($toutesLesAnnonces as $unArticle) {


            $row++;
            if ($row < 1500) {

                $lienArticle = "http://www.letssleep.argetweb.fr/montre-revolutionnaire/dormir/article/" . $unArticle->id . "/" . urlencode(strtolower(stripslashes(preg_replace("#\s#", "-", $unArticle->nom))));
                $length_max = 200;
                $description = stripslashes($unArticle->texte);
                $displayText = htmlspecialchars(preg_replace("#&nbsp;#", "", strip_tags($description)));
                $var = "";
                $length = strlen($displayText);
                if ($length > $length_max) {
                    $var = " [...]";
                }
                $displayText = wordwrap($displayText, $length_max, ";-;-;");
                $tableau = explode(";-;-;", $displayText);
                $description = $tableau[0];


                $xml .= "
		    
		    <item>";

                $xml .= "
			    <title>" . stripslashes($unArticle->nom) . "</title>
			    <description>" . $description . "</description>
			    <link>" . $lienArticle . "</link>
                            <published>" . $unArticle->date . "+00:00</published>
                            <updated>" . $unArticle->date . "+00:00</updated>
                            <pubDate>" . $unArticle->date . "+00:00</pubDate>
			    <g:id>" . $unArticle->id . "</g:id>
		    </item>";
            }
        }

        $xml .= "
        </channel>
    </rss>
                   ";


        fwrite($fichier, $xml);
        fclose($fichier);
    }

}

?>