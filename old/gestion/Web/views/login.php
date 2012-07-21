<div id="login">

    <h2>Connexion au Gestionnaire de Contenu</h2>

    <div class="formulaire">
        <form name="create" method="post" action="index.php?content=login">

            <fieldset>
                <legend style="color:#5b5656">Authentification</legend>

                <?php if (isset($_GET['login'])) {
                    if ($_GET['login'] == "0") {
                        echo "<span style='color:red'>Mauvais identifiants, veuillez r&eacute;essayer.</span>";
                    }
                } ?>

                <p>
                    <label for="login">
                        <b>Email:</b><br/>
                    </label>
                    <input type="text" name="login" style="width:70%;" required />

                </p>
                <p>
                    <label for="password">
                        <b>Mot de passe:</b><br/>
                    </label>
                    <input type="password" name="password" style="width:70%;" required />

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
        <a href="../" title="Revenir &agrave; la page pr&eacute;c&eacute;dente"  >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retour sur le site web</a>
        <span style="float:right; margin-right: 20px; "><a style="background: url('') left no-repeat;" href="index.php?content=lostpassword" title="Réinitialiser mot de passe">[ Mot de passe oublié ? ]</a>
    </div>


</div>