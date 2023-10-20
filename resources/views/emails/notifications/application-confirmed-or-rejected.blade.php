<h1>Ծանուցում</h1>


<?php

//TODO There is currently no email view template
?>

<?php if ($status === 'confirmed') {
    ?>
    դիմումը հաստատվել է, ընտրված հաշտարարի վերաբերյալ լրացուցիչ կծանուցվեք
<?php } else{  echo $reason ?>

<?php }

