<div id="admin">

    <h2>Affichage des administrateurs</h2>

    <?php
    if (isset($_GET['erreur'])) {

        if ($_GET['erreur']) {
            echo "<span style='color:red'>Une erreur est survenue...</span>";
        } else {
            echo "<span style='color:#106409'>Modification r&eacute;ussie !</span>";
        }
        echo "<br/>";
    }
    ?>



    <form name="tri" method="post" action="index.php?content=admin">

        <p class='triselect'>
            <label for="select">Trier par: </label>
            <select name="select" onchange="javascript:document.tri.submit();">
                <option value="none" SELECTED>...</option>
                <option value="id">ID</option>
                <option value="timestamp">Timestamp</option>
                <option value="fonction">fonction</option>
                <option value="privilege">privilege</option>
                <option value="nom">nom</option>
                <option value="prenom">prenom</option>
                <option value="email">email</option>
                <option value="password">password</option>
                <option value="tel">tel</option>
                <option value="date_inscription">date_inscription</option>
            </select>

            <?php
            if (isset($_POST['selectmsg'])) {
                echo "<input type='hidden' name='selectmsg' value='" . $_POST['selectmsg'] . "' />";
            } elseif (isset($_GET['selectmsg'])) {
                echo "<input type='hidden' name='selectmsg' value='" . $_GET['selectmsg'] . "' />";
            }
            ?>



            <?php
            if (isset($_POST['ordre'])) {

                if ($_POST['ordre'] == "ASC") {
                    ?>

                    <input type="radio" name="ordre" value="ASC" class="styled" checked><label>Ascendant</label>
                    <input type="radio" name="ordre" value="DESC" class="styled"><label>Descendant</label>

                <?php } else { ?>

                    <input type="radio" name="ordre" value="ASC" class="styled"><label>Ascendant</label>
                    <input type="radio" name="ordre" value="DESC" class="styled" checked><label>Descendant</label>

                    <?php
                }
            } elseif (isset($_GET['ordre'])) {

                if ($_GET['ordre'] == "ASC") {
                    ?>

                    <input type="radio" name="ordre" value="ASC" class="styled" checked><label>Ascendant</label>
                    <input type="radio" name="ordre" value="DESC" class="styled"><label>Descendant</label>

                <?php } else { ?>

                    <input type="radio" name="ordre" value="ASC" class="styled"><label>Ascendant</label>
                    <input type="radio" name="ordre" value="DESC" class="styled" checked><label>Descendant</label>

                    <?php
                }
            } elseif (isset($_POST['ordre-hidden'])) {

                if ($_POST['ordre-hidden'] == "ASC") {
                    ?>

                    <input type="radio" name="ordre" value="ASC" class="styled" checked><label>Ascendant</label>
                    <input type="radio" name="ordre" value="DESC" class="styled"><label>Descendant</label>

                <?php } else { ?>

                    <input type="radio" name="ordre" value="ASC" class="styled"><label>Ascendant</label>
                    <input type="radio" name="ordre" value="DESC" class="styled" checked><label>Descendant</label>

                    <?php
                }
            } else {
                ?>

                <input type="radio" name="ordre" value="ASC" class="styled"><label>Ascendant</label>
                <input type="radio" name="ordre" value="DESC" class="styled" checked><label>Descendant</label>

            <?php } ?>

        </p>

    </form>

    <div class="toutselectionner">
        <label>Tout s&eacute;lectionner: </label><br/>
        &nbsp;&nbsp;<input type='checkbox' name='checkall' class='checkall' onclick='checkall(this.checked);' />
    </div>
    <div class="msgparpage">
        <div class="form">
            <form name="msgpage" method="post" action="index.php?content=admin">
                <label for="selectmsg">Admins par page: </label>
                <select name="selectmsg" onchange="javascript:document.msgpage.submit();">
                    <option value="none" SELECTED>...</option>
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="30">30</option>
                    <option value="40">40</option>
                    <option value="50">50</option>
                </select>
                <?php
                if (isset($_POST['ordre'])) {
                    echo "<input type='hidden' name='ordre-hidden' value='" . $_POST['ordre'] . "' />";
                } elseif (isset($_GET['ordre'])) {
                    echo "<input type='hidden' name='ordre-hidden' value='" . $_GET['ordre'] . "' />";
                } elseif (isset($_POST['ordre-hidden'])) {
                    echo "<input type='hidden' name='ordre-hidden' value='" . $_POST['ordre-hidden'] . "' />";
                }

                if (isset($_POST['select'])) {
                    echo "<input type='hidden' name='select' value='" . $_POST['select'] . "' />";
                } elseif (isset($_GET['select'])) {
                    echo "<input type='hidden' name='select' value='" . $_GET['select'] . "' />";
                }
                ?>
            </form>
        </div>
    </div>
    <div class="clear"></div>

    <table class="bordered">

        <thead>
            <tr>
                <th class='checkbox'>
                    S&eacute;lection
                </th>
                <th class='id'>
                    ID
                </th>
                <th class='fonction'>
                    Fonction
                </th>
                <th class='privilege'>
                    Privilege
                </th>
                <th class='nom'>
                    Nom
                </th>
                <th class='prenom'>
                    Prenom
                </th>
            </tr>
        </thead>
        <tbody>
        <form id="formselection" name="selection" method="post" action="index.php?content=admin">
            <?php
            foreach ($liste as $uneLigne) {
                ?>

                <tr class="uneLigne">

                    <td class='checkbox'>
                        <input type='checkbox' name='selection[]' class='styled' value='<?php echo $uneLigne->getId(); ?>' />
                    </td>
                    <td class='id'>
                        <a href='index.php?content=admin-edit&id=<?php echo $uneLigne->getId(); ?>'><?php echo $uneLigne->getId(); ?></a>
                        <span class='edit-delete'>
                            <a href='index.php?content=admin-edit&id=<?php echo $uneLigne->getId(); ?>' title='Editer'>Editer</a> | 
                            <a href="javascript:if(confirm('Voulez-vous vraiment supprimer cet &eacute;l&eacute;ment ?')){ document.location='index.php?content=admin-delete&id=<?php echo $uneLigne->getId(); ?>'; };" title="Supprimer">Supprimer</a>
                        </span>
                    </td>
                    <td class='fonction'>
                        <a href='index.php?content=admin-edit&id=<?php echo stripslashes($uneLigne->getId()); ?>'><?php echo stripslashes($uneLigne->getfonction()); ?></a>
                    </td>
                    <td class='privilege'>
                        <a href='index.php?content=admin-edit&id=<?php echo stripslashes($uneLigne->getId()); ?>'><?php echo stripslashes($uneLigne->getprivilege()); ?></a>
                    </td>
                    <td class='nom'>
                        <a href='index.php?content=admin-edit&id=<?php echo stripslashes($uneLigne->getId()); ?>'><?php echo stripslashes($uneLigne->getnom()); ?></a>
                    </td>
                    <td class='prenom'>
                        <a href='index.php?content=admin-edit&id=<?php echo stripslashes($uneLigne->getId()); ?>'><?php echo stripslashes($uneLigne->getprenom()); ?></a>
                    </td>

                </tr>

                <?php
            }
            ?>
        </form>
        </tbody>
    </table>


    <?php
    if (isset($getter)) {
        paginationWithImage($getter);
    } else {
        paginationWithImage(null);
    }
    ?>

    <div class="boutons-content">
        <br/>
        <a href='javascript:document.selection.submit();' title="Supprimer des Admins" onclick="if ( confirm( 'Vous etes sur le point de supprimer cet &eacute;l&eacute;ment.\n\nAnnuler pour abandonner, \nOK pour le supprimer.' ) ) { return true;}return false;">Supprimer la selection</a>
        <a href="index.php?content=admin-add" title="Ajouter un Admin" >Ajouter un Admin</a>

    </div>
    <br/>

</div>