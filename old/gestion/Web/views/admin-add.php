<div id='admin-add'>
    
    <h2>Ajout d'un administrateur</h2>
    
    <div class='formulaire'>
    
	<form name='create' method='post' action='index.php?content=admin-add' enctype='multipart/form-data'>
	
	    <fieldset>
	    
		<legend style='color:#5b5656'>Formulaire</legend>
		<p>
		    <label for='fonction'>
			Fonction: <br/>
		    </label>
		    <input type='text' name='fonction' maxlength='100' required  />
		</p>
		<p>
		    <label for='privilege'>
			Privilege: <br/>
		    </label>
                    <select name="privilege">
                        <option value="0" SELECTED>0</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                    </select>
		</p>
		<p>
		    <label for='nom'>
			Nom: <br/>
		    </label>
		    <input type='text' name='nom' maxlength='100' required  />
		</p>
		<p>
		    <label for='prenom'>
			Prenom: <br/>
		    </label>
		    <input type='text' name='prenom' maxlength='100' required  />
		</p>
		<p>
		    <label for='email'>
			Email: <br/>
		    </label>
		    <input type='email' name='email' required  />
		</p>
		<p>
		    <label for='password'>
			Password: <br/>
		    </label>
		    <input type='password' name='password' maxlength='100' required  />
		</p>
		<p>
		    <label for='tel'>
			Tel: <br/>
		    </label>
		    <input type='tel' name='tel' maxlength='20'  />
		</p>
		<p>
		    <label for='image'>
			Image: <br/>
		    </label>
		    <input type='file' name='image'  />
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