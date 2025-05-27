<?php
include '../config.php';

$sql = "SELECT * FROM professores";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row['id'] . " - Nome: " . $row['nome'] . " - CPF: " . $row['cpf'] . " - Email: " . $row['email'];
        echo " <a href='edit.php?id=" . $row['id'] . "'>Editar</a> ";
        echo " <a href='delete.php?id=" . $row['id'] . "'>Excluir</a><br>";
    }
} else {
    echo "Nenhum professor encontrado!";
}
?>
