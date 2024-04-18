<?php

class ApipedidosController extends AppController {

    var $name = "ApipedidosController";

    function index() {
        $recetas = $this->Receta->find('all');
        $this->set(compact('recetas'));
    }

    function view($id) {
        $receta = $this->Receta->findById($id);
        $this->set(compact('receta'));
    }

    function edit($id) {
        $this->Receta->id = $id;
        if ($this->Receta->save($this->data)) {
            $message = 'Saved';
        } else {
            $message = 'Error';
        }
        $this->set(compact("message"));
    }

    function delete($id) {
        if ($this->Receta->delete($id)) {
            $message = 'Deleted';
        } else {
            $message = 'Error';
        }
        $this->set(compact("message"));
    }

}

?>