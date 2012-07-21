<div id='admin-edit'>
    
    <h2>Edition des administrateurs</h2>
    
    <div class='formulaire'>
    
	<form name='edit' method='post' action='index.php?content=admin-edit&id=<?php echo $_GET['id']; ?>' enctype='multipart/form-data'>
	
	    <fieldset>
	    
		<legend style='color:#5b5656'>Formulaire</legend>
		
		
		<p>
		    <label for='fonction'>
			Fonction: <br/>
		    </label>
		    <input type='text' name='fonction' maxlength='100' required  value="<?php echo htmlspecialchars(stripslashes($objet->fonction)); ?>" />
		</p>
		<p>
		    <label for='privilege'>
			Privilege: <br/>
		    </label>
                    <select name="privilege">
                        <?php
                            for($i = 0; $i < 10; $i++){
                                
                                if($objet->privilege == $i) echo '<option value="'.$i.'" SELECTED>'.$i.'</option>';
                                    else echo '<option value="'.$i.'">'.$i.'</option>';
                                
                            }
                        ?>

                    </select>
		</p>
		<p>
		    <label for='nom'>
			Nom: <br/>
		    </label>
		    <input type='text' name='nom' maxlength='100' required  value="<?php echo htmlspecialchars(stripslashes($objet->nom)); ?>" />
		</p>
		<p>
		    <label for='prenom'>
			Prenom: <br/>
		    </label>
		    <input type='text' name='prenom' maxlength='100' required  value="<?php echo htmlspecialchars(stripslashes($objet->prenom)); ?>" />
		</p>
		<p>
		    <label for='email'>
			Email: <br/>
		    </label>
		    <input type='email' name='email' required value="<?php echo htmlspecialchars(stripslashes($objet->email)); ?>" />
		</p>
		<p>
		    <label for='password'>
			Password: <br/>
		    </label>
		    <input type='password' name='password' maxlength='100' required  value="password" />
		</p>
		<p>
		    <label for='tel'>
			Tel: <br/>
		    </label>
		    <input type='tel' name='tel' maxlength='20'  value="<?php echo htmlspecialchars(stripslashes($objet->tel)); ?>" />
		</p>
		<p>
		    <label for='image'>
			Image: <br/>
		    </label>
		    <input type='file' name='image'  />
		    
		    <br/><img src='<?php echo $objet->image; ?>' alt='Image indisponible'/>
		</p>
    
		<br/>

                <div class='boutons'>
		
                    <input type='submit' value='Envoyer' class='boutonenvoyer' />
		    
                </div>
		
                <br/>
    
	    </fieldset>
	    
	</form>
	
    </div>
    
    
    <br/><br/><br/>
    <div class='back'>
    
        <a href='index.php?content=admin' title='Revenir &agrave; la page pr&eacute;c&eacute;dente' >&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Revenir &agrave; la page pr&eacute;c&eacute;dente</a>
	
    </div>
    
    
</div>