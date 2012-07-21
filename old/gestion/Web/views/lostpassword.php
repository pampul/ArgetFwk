<div id="login">

    <h2>R&eacute;cup&eacute;rer votre mot de passe</h2>

    <p>Veuillez saisir votre adresse de messagerie. Vous recevrez un courriel contenant la démarche à suivre pour changer votre mot de passe.</p>

    <div class="formulaire">
        <form name="create" method="post" action="index.php?content=lostpassword">

            <fieldset>
                <legend style="color:#5b5656">Authentification</legend>

                <?php
                if (isset($_GET['wrong'])) {
                    if ($_GET['wrong'] == "0") {
                        echo "<span style='color:red'>Aucun compte n'est rattach&eacute; &agrave;� cette adresse email.</span>";
                    } else {
                        echo "<span style='color:green'>Un email vient de vous etre envoy&eacute;.</span>";
                    }
                }
                ?>

                <p>
                    <label for="login">
                        <b>Email:</b><br/>
                    </label>
                    <input type="text" name="login" style="width:70%;" required />

                </p>

                <br/>
                <div class="boutons">
                    <input type="submit" value="Valider" class="boutonenvoyer" />
                </div>
                <br/>
            </fieldset>

        </form>
    </div>
    <br/><br/><br/>

    <div class="back">
        <a href="index.php?content=login" title="Revenir &agrave; la page pr&eacute;c&eacute;dente"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Page précédente</a>
    </div>


</div>