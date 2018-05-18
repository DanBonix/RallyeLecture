<?php echo validation_errors(); ?>
<?php echo form_open('Account/create'); ?>
<div>Nom                       : <input type="text" name="nom" /></div>
<div>Prénom                    : <input type="text" name="prenom" /></div>
<div>Email                     : <input type="text" name="login" /></div>
<div>Mot de Passe              : <input type="text" name="password" /></div> 
<div>Confirmez le Mot de Passe : <input type="text" name="confirm" /></div>
<br><button type="submit"> Créer le compte</button>
<?php echo form_close(); ?>
    

