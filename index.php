<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Projet PHP</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

</head>

<body>
    <?php
    require('db-management.php');
    global $error;
    global  $message, $action;
    global $id, $libelle, $prix_unitaire, $quantite, $typeDeProduit, $description;
    if (!empty($_GET)) {
        $action = $_GET['action'];
        $id = $_GET['id'];
        if ($action  ==  'edit') {
            $produits = getProduit($id);
            foreach ($produits->fetchAll() as $k => $v) {
                $id = $v['id'];
                $libelle  = $v['libelle'];
                $prix_unitaire  = $v['prix_unitaire'];
                $quantite  = $v['quantite'];
                $typeDeProduit  = $v['typeDeProduit'];
                $description  = $v['descript'];
            }
        } else {
            delete($id);
            $id = null;
        }
    }
    if (!empty($_POST)) {
        $id = $_POST['id'];
        $libelle = $_POST['libelle'];
        $prix_unitaire = $_POST['prix_unitaire'];
        $quantite = $_POST['quantite'];
        $typeDeProduit = $_POST['typeDeProduit'];
        $description = $_POST['description'];

        if (empty($libelle)) {
            $error = "Le libellé est obligatoire";
        } else if (empty($quantite) || is_numeric($quantite) != 1) {
            $error = "La quantité est obligatoire et doit être numérique";
        } else if (empty($prix_unitaire) || is_numeric($prix_unitaire)  != 1) {
            $error = "Le prix unitaire est obligatoire et doit être numérique";
        } else if (empty($typeDeProduit)) {
            $error = "Le type de produit est obligatoire";
        } else if (empty($description)) {
            $error = "La description est obligatoire";
        } else {
            if (!empty($id)) {
                update($id, $libelle, $prix_unitaire, $quantite, $description, $typeDeProduit);
                $message = "Produit mis à jour avec succès";
            } else {
                insert($libelle, $prix_unitaire, $quantite, $description, $typeDeProduit);
                $message = "Produit ajouté avec succès";
            }
            $id = null;
            $libelle  = null;
            $description = null;
            $prix_unitaire = null;
            $quantite = null;
            $description  = null;
            $typeDeProduit  = null;
        }
    }
    ?>
    <div class="card-body">
        <h2 style="text-align: center;">VENDE DE PRODUIT</h2>
    </div>
    <div class=" col-md-3">
        <div>
            <div class="panel  panel-info">
                <div class="panel-heading">
                 <h4 style="text-align: center;" >AJOUTER DES PRODUIT</h4>  
                </div>
                <div class="panel-body">
                    <form action="index.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $id ?>">
                        <div class="form-group">
                            <label>Libéllé</label>
                            <input class="form-control" name="libelle" value="<?php echo $libelle ?>">
                        </div>
                        <div class="form-group">
                            <label>Quantité</label>
                            <input class="form-control" name="quantite" value="<?php echo $quantite ?>">
                        </div>
                        <div class="form-group">
                            <label>Prix Unitaire</label>
                            <input class="form-control" name="prix_unitaire" value="<?php echo $prix_unitaire ?>">
                        </div>
                        <div class="form-group">
                            <label>TypedeProduit</label>
                            <select class="form-control" name="typeDeProduit" value="<?php echo $typeDeProduit ?>">
                                <option>Alimentaire</option>
                                <option>Electronique</option>
                                <option>Vetement</option>
                                <option>Autre</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea class="form-control" name="description" rows="3">
                            <?php echo $description ?>
                            </textarea>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-success" type="submit">
                                <?php
                                if ($action == 'edit') {
                                    echo 'Mettre à jour';
                                } else {
                                    echo 'Ajouter';
                                }
                                ?>
                            </button>
                        </div>
                        <?php

                        if (!empty($error)) {
                            echo "<div class='alert alert-danger' style='margin-top:20px'>" . $error . "
                            </div>";
                        }
                        if (!empty($message)) {
                            echo "<div class='alert alert-success' style='margin-top:20px'>" . $message . "
                            </div>";
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="panel panel-info">
            <div class="panel-heading" style="display:flex;align-items: center;justify-content: space-between;color:crimson">
                Liste des produits
            </div>
            <div class="table mx-auto">
                <table class="table table-success table-striped">
               
                    <thead class="thead-dark">
                    
                        <td>ID</td>
                        <td>Libéllé</td>
                        <td>Prix Unitaire</td>
                        <td>Quantité</td>
                        <td>Type</td>
                        <td>Description</td>
                        <td>Action</td>
                    </thead>
                    <tbody>
                        <?php
                        $allProduits = produits();
                        foreach ($allProduits->fetchAll() as $k => $v) {
                            echo "<tr>
                            <td>" . $v['id'] . "</td>
                            <td >" . $v['libelle'] . "</td>
                            <td>" . $v['prix_unitaire'] . "</td>
                            <td>" . $v['quantite'] . "</td>
                            <td>" . $v['typeDeProduit'] . "</td>
                            <td>" . $v['descript'] . "</td>
                            <td style='display:flex;justify-content: space-around;'>
                            <form action='index.php?' >
                            <input type='hidden' value='" . $v['id'] . "'  name='id'>
                            <input type='hidden' name='action' value='edit'>
                            <button class='btn btn-sm btn-info'>
                            <i class='fa fa-pencil'></i>
                            </button>
                            </form>
                         
                            <button class='btn btn-sm btn-danger' type='button' data-toggle='modal' data-target='#myModal" . $v['id'] . "'>
                            <i class='fa fa-trash'></i>
                            </button>
                            <div class='modal fade' id='myModal" . $v['id'] . "' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                <div class='modal-header'>
                                    <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
                                    <h4 class='modal-title' id='myModalLabel'>Modal title</h4>
                                </div>
                                <div class='modal-body'>
                                   Êtes vous sûr de vouloir supprimer le produit avec ID " . $v['id'] . "?
                                </div>
                                <div class='modal-footer'>
                                <form action='index.php' >
                                <button type='button' class='btn btn-default' data-dismiss='modal'>Non</button>
                                    <input type='hidden' value='" . $v['id'] . "' name='id'>
                                    <input type='hidden' name='action' value='remove'>
                                    <button type='submit' class='btn btn-danger'>Oui</button>
                                    </form>
                                </div>
                                </div>
                            </div>
                            </div>
                      
                            </td>
                            </tr>";
                        }

                        ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>