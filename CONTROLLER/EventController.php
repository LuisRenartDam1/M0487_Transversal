<?php

require_once __DIR__ . '/../MODEL/db.php';

// Create CRUD

$eventController = new EventController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['create'])) {
        $eventController->create();
    }

    if (isset($_POST['update'])) {
        $eventController->update();
    }

    if (isset($_POST['delete'])) {
        $eventController->delete();
    }
}

if (
    isset($_GET['action']) &&
    $_GET['action'] === 'read'
) {
    $eventController->read();
}

class EventController
{

    public function create()
    {
        $c = (new Database())->getConnection();

        $q = $c->prepare(
            'INSERT INTO productos (name, price, amount, stock)
             VALUES (?, ?, ?, ?)'
        );

        $ok = $q->execute([
            $_POST['name'],
            $_POST['price'],
            $_POST['amount'],
            $_POST['stock']
        ]);

        header(
            'Location: ../VIEW/events.php?create=' .
                ($ok ? 1 : 0)
        );

        exit();
    }

    public function read()
    {

        $c = (new Database())->getConnection();

        $q = $c->query(
            'SELECT id_product, name, price, amount, stock
             FROM productos
             ORDER BY id_product DESC'
        );

        header('Content-Type: application/json');

        echo json_encode(
            $q->fetchAll(PDO::FETCH_ASSOC)
        );

        exit();
    }

    public function update()
    {

        $c = (new Database())->getConnection();

        $q = $c->prepare(
            'UPDATE productos
             SET name = ?, price = ?, amount = ?, stock = ?
             WHERE id_product = ?'
        );

        $ok = $q->execute([
            $_POST['name'],
            $_POST['price'],
            $_POST['amount'],
            $_POST['stock'],
            $_POST['id']
        ]);

        header(
            'Location: ../VIEW/events.php?update=' .
                ($ok ? 1 : 0)
        );

        exit();
    }

    public function readId($id)
    {
        if (!$id) {
            return null;
        }

        $c = (new Database())->getConnection();

        $q = $c->prepare(
            'SELECT id_product, name, price, amount, stock
            FROM productos
            WHERE id_product = :id'
        );

        $q->execute([
            ':id' => $id
        ]);

        return $q->fetch(PDO::FETCH_ASSOC);
    }


    public function delete()
    {

        $c = (new Database())->getConnection();

        $q = $c->prepare(
            'DELETE FROM productos
             WHERE id_product = ?'
        );

        $ok = $q->execute([
            $_POST['id']
        ]);

        header(
            'Location: ../VIEW/events.php?delete=' .
                ($ok ? 1 : 0)
        );

        exit();
    }
}
