<?php

declare(strict_types=1);

namespace App\Controllers;


use Exception;
use App\Models\Product;
use Framework\Controller;
use Framework\Validate;

class Products extends Controller
{

    public function __construct(private Product $model) {}

    public function index()
    {
        $products = $this->model->findAll();

        return $this->view("Products/index.php", ["products" => $products]);
    }

    public function show($id)
    {
        $product = $this->model->find($id);

        if ($product === false) {
            throw new Exception("Product not found");
        }

        return $this->view("Products/show.php", ["product" => $product]);
    }

    public function new()
    {
        return $this->view("Products/new.php");
    }

    public function create()
    {
        $data['name'] = trim($this->request->post['name']);
        $data['description'] = trim($this->request->post['description']) ?? null;

        if (! Validate::string($data['name'])) {

            $this->addError("name", "Name is required");

            return $this->view("Products/new.php", ["errors" => $this->errors, "product" => $data]);
        }


        $this->model->insert($this->request->post);

        $id = $this->model->getLastId();

        return $this->redirect("/products/$id/show");
    }

    public function edit($id)
    {
        $product = $this->model->find($id);

        if ($product === false) {
            throw new Exception("Product not found");
        }

        return $this->view("Products/edit.php", ["product" => $product]);
    }

    public function update($id)
    {
        $product = $this->model->find($id);

        if ($product === false) {
            throw new Exception("Product not found");
        }

        $product['name'] = trim($this->request->post['name']);

        $product['description'] = trim($this->request->post['description']) ?? null;

        if (! Validate::string($product['name'])) {

            $this->addError("name", "Name is required");

            return $this->view("Products/edit.php", ["errors" => $this->errors, "product" => $product]);
        }

        $this->model->update($product);

        return $this->redirect("/products/$id/show");
    }

    public function delete($id)
    {
        $product = $this->model->find($id);

        if ($product === false) {
            throw new Exception("Product not found");
        }

        return $this->view("Products/delete.php", ["product" => $product]);
    }

    public function destroy($id)
    {
        if ($this->model->delete($id)) {

            return $this->redirect("/products/index");
        } else {
            throw new Exception("Delete product failed");
        }
    }
}
