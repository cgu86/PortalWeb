<?php

use mvc\interfaces\controllerActionInterface;
use mvc\controller\controllerClass;
use mvc\config\configClass as config;
use mvc\request\requestClass as request;
use mvc\routing\routingClass as routing;
use mvc\session\sessionClass as session;
use mvc\i18n\i18nClass as i18n;

/**
 * Description of ejemploClass
 *
 * @author Julian Lasso <ingeniero.julianlasso@gmail.com>
 */
class createActionClass extends controllerClass implements controllerActionInterface {

  public function execute() {
    try {
      if (request::getInstance()->isMethod('POST')) {

        $usuario = trim(request::getInstance()->getPost(usuarioTableClass::getNameField(usuarioTableClass::USER, true)));
        $password = request::getInstance()->getPost(usuarioTableClass::getNameField(usuarioTableClass::PASSWORD, true) . '_1');
        $password2 = request::getInstance()->getPost(usuarioTableClass::getNameField(usuarioTableClass::PASSWORD, true) . '_2');

        $this->validate($usuario, $password, $password2);


        $data = array(
            usuarioTableClass::USER => $usuario,
            usuarioTableClass::PASSWORD => md5($password)
        );
        usuarioTableClass::insert($data);
        routing::getInstance()->redirect('usuario', 'index');
      } else {
        routing::getInstance()->redirect('usuario', 'index');
      }
    } catch (PDOException $exc) {
      echo $exc->getMessage();
      echo '<br>';
      echo '<pre>';
      print_r($exc->getTrace());
      echo '</pre>';
    }
  }

  private function validate($usuario, $password, $password2) {
    $flag = false;


    if (empty($usuario)) {

      session::getInstance()->setError(i18n::__(00006, NULL, 'errors'));
      $flag = true;
      session::getInstance()->setFlash(usuarioTableClass::getNameField(usuarioTableClass::USER, true), true);
    }

    if (strlen($usuario) > usuarioTableClass::USER_LENGTH) {
      session::getInstance()->setError(i18n::__(00004, NULL, 'errors', array('%user%' => $usuario, '%caracteres%' => usuarioTableClass::USER_LENGTH)));

      $flag = true;
      session::getInstance()->setFlash(usuarioTableClass::getNameField(usuarioTableClass::USER, true), true);
    }

    if ($password !== $password2) {

      session::getInstance()->setError(i18n::__(00005, NULL, 'errors'));
      $flag = true;
      session::getInstance()->setFlash(usuarioTableClass::getNameField(usuarioTableClass::PASSWORD, true), true);
    }

    if (empty($password)) {

      session::getInstance()->setError(i18n::__(00007, NULL, 'errors'));
      $flag = true;

      session::getInstance()->setFlash(usuarioTableClass::getNameField(usuarioTableClass::USER, true), true);
    }


    if (empty($password2)) {

      session::getInstance()->setError(i18n::__(00009, NULL, 'errors'));
      $flag = true;
      session::getInstance()->setFlash(usuarioTableClass::getNameField(usuarioTableClass::PASSWORD, true), true);
      session::getInstance()->setFlash(usuarioTableClass::getNameField(usuarioTableClass::USER, true), true);
    }



    //validador de existencia de usuario

    $fields = array(
        usuarioTableClass::USER
    );
    $objUsuario = usuarioTableClass::getAll($fields);

    foreach ($objUsuario as $key) {
      if ($key->user_name === $usuario) {
        session::getInstance()->setError(i18n::__(00010, NULL, 'errors'));
        $flag = true;
        session::getInstance()->setFlash(usuarioTableClass::getNameField(usuarioTableClass::USER, true), true);
      }
    }

    //condicion de caracteres invalidos
    
    $caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ ";

    for ($i = 0; $i < strlen($usuario); $i++) {
      if (strpos($caracteres, substr($usuario, $i, 1)) === false) {

        session::getInstance()->setError(i18n::__(00011, NULL, 'errors'),array('%char%' => $usuario));
        $flag = true;
        session::getInstance()->setFlash(usuarioTableClass::getNameField(usuarioTableClass::USER, true), true);
      }
    }



if ($flag === true) {

      request::getInstance()->setMethod('GET');
      routing::getInstance()->forward('usuario', 'insert');
    }
  }

}
