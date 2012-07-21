<?php ?>

<div id='log'>

    <h2>Logs de votre site web</h2>

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



    <form name="tri" method="post" action="index.php?content=logs">

        <p class='triselect'>
            <label for="select">Trier par: </label>
            <select name="select" onchange="javascript:document.tri.submit();">
                <option value="none" SELECTED>...</option>
                <option value="id">ID</option>
                <option value="timestamp">Timestamp</option>
                <option value="date">date</option>
                <option value="administrateur">administrateur</option>
                <option value="type">type</option>
                <option value="categorie">categorie</option>
                <option value="nom">nom</option>
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

    <div class="msgparpage">
        <div class="form">
            <form name="msgpage" method="post" action="index.php?content=logs">
                <label for="selectmsg">Logs par page: </label>
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

                <th class='id'>
                    ID
                </th>
                <th class='date'>
                    Date
                </th>
                <th class='administrateur'>
                    Administrateur
                </th>
                <th class='type'>
                    Type
                </th>
                <th class='categorie'>
                    Categorie
                </th>
                <th class='nom'>
                    Nom
                </th>
            </tr>
        </thead>
        <tbody>
        <form id="formselection" name="selection" method="post" action="index.php?content=log">
            <?php
            foreach ($liste as $uneLigne) {
                ?>

                <tr class='uneLigne'>

                    <td class='id'>
                        <i><?php echo $uneLigne->getId(); ?></i>
                    </td>
                    <td class='date'>
                        <span style="font-weight: normal; font"><i><?php echo stripslashes($uneLigne->getdate()); ?></i></span>
                    </td>
                    <td class='administrateur'>
                        <span style="font-weight: normal;"><i><?php echo stripslashes($uneLigne->getadministrateur()); ?></i></span>
                    </td>
                    <td class='type'>
                        <span style="font-weight: normal;"><i><?php echo stripslashes($uneLigne->gettype()); ?></i></span>
                    </td>
                    <td class='categorie'>
                        <span style="font-weight: normal;"><i><?php echo stripslashes($uneLigne->getcategorie()); ?></i></span>
                    </td>
                    <td class='nom'>
                        <span style="font-weight: normal;"><i><?php echo stripslashes($uneLigne->getnom()); ?></i></span>
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

    <br/>


</div>