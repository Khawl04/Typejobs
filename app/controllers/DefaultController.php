<?php
class DefaultController extends Controller
{
    public function sobre() {
        $this->view('/layouts/sobre');
    }

    public function contacto() {
        $this->view('/layouts/contacto');
    }
}
