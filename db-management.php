<?php
$servername = "localhost";
$username  = "root";
$password = "";
$database = "gestion_boutique";
try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE IF NOT EXISTS $database";
    $sqlTable = "CREATE TABLE IF NOT EXISTS produit(
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        libelle VARCHAR(100),
        descript VARCHAR(250),
        quantite DOUBLE(10,2),
        prix_unitaire INT(6),
        typeDeProduit VARCHAR(50)
    )";
    $conn->exec($sql);
    $conn->exec("use $database");
    $conn->exec($sqlTable);
    $conn = null;
} catch (PDOException $e) {
    echo $e->getMessage();
}

function open()
{
    global $servername, $database, $username, $password;
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (\PDOException $e) {
        echo $e->getMessage();
    }
}
function produits()
{
    try {
        $conn = open();
        $sql = "SELECT * from produit";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $conn = null;
        return $stmt;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}
function insert($libelle, $prix_unitaire, $quantity, $description, $typeDeProduit)
{
    try {
        $conn = open();
        $sql = "INSERT INTO produit(libelle, descript, quantite,prix_unitaire,typeDeProduit ) values('$libelle','$description', '$quantity', '$prix_unitaire','$typeDeProduit')";
        $conn->exec($sql);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}
function update($id, $libelle, $prix_unitaire, $quantity, $description, $typeDeProduit)
{
    try {
        $conn = open();
        $sql = "UPDATE produit  SET libelle = '$libelle', descript = '$description', quantite = '$quantity', prix_unitaire = '$prix_unitaire',typeDeProduit = '$typeDeProduit' WHERE id='$id'";
        $conn->exec($sql);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}
function delete($id)
{
    try {
        $conn = open();
        $sql = "DELETE FROM produit WHERE id= $id";
        $conn->exec($sql);
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}
function getProduit($id)
{
    try {
        $conn = open();
        $sql = "SELECT * from produit where  id= $id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $conn = null;
        return $stmt;
    } catch (PDOException $e) {
        echo $sql . "<br>" . $e->getMessage();
    }
}
