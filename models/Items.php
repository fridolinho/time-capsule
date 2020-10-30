<?php
class Item {
    private $conn;
    private $table ='sys_items';

    public $id;
    public $supplier;
    public $image;

    public function __construct($db) {
        $this->conn =$db;
    }

    public function getProduct($token) {
        $query = 'SELECT image FROM innovation.sys_items WHERE token_number = ?';
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$token]);
        $product = $stmt->fetch();

        return $product;
    }
}