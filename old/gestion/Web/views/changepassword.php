<div id="login">

    <h2>Changer votre mot de passe</h2>
    
    
    <?php
    
        if(!$verif){
            
            ?>
    <p><span style="color: red;">Attention, vous ne pouvez pas changer de mot de passe pour cette adresse email.</span></p>
            <?php
            
        }else{
            
    ?>
 
    <div class="formulaire">
        <form name="create" method="post" action="index.php?content=changepassword">

            <fieldset>
                <legend style="color:#5b5656">Nouveau mot de passe</legend>

                <p>
                    <label for="login">
                        <b>Nouveau mot de passe:</b><br/>
                    </label>
                    <input type="password" name="newpassword" style="width:70%;" required />
                    <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>" />
                    <input type="hidden" name="ui" value="<?php echo $_GET['ui']; ?>" />

                </p>
                
                <br/>
                <div class="boutons">
                    <input type="submit" value="Valider" class="boutonenvoyer" />
                </div>
                <br/>
            </fieldset>

        </form>
    </div>
    
    
    <?php
    
        }
        
        ?>


</div>