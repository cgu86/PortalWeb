<?php use mvc\routing\routingClass as routing ?>
<?php use mvc\i18n\i18nClass as i18n ?>
<?php use mvc\view\viewClass as view ?>
<div class="jumbotron">
            <h1>Modificacion de Usuario Credencial</h1>
            <p>Por favor llenar los siguientes campos</p>

        </div>

            <?php view::includePartial('usuarioCredencial/updateFormUsuarioCredencial', array('objCredencial1' => $objCredencial1,'objUsuarioCredencial' =>$objUsuarioCredencial)) ?>
            