<?php
use yii\helpers\Url;


$base = Url::base();

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="body-content">

        <div class="row">
            <a class="btn btn-info" href="<?= $base ?>/index.php?site/gettables">
                Obtener tabla usuarios
            </a>

            <?php 
            if(isset($result)){
                echo 'hola';
                var_dump($result);
            }
            ?>
        </div>

    

</div>
