<html>
    <head></head>
    <body style="background-color: <?php echo $_REQUEST['bgcolor']; ?>">
        <?php
        if(isset( $_REQUEST['showcolor'])){
            echo "<p>Select color:  ".$_REQUEST['bgcolor']."</p>";
        }
        ?>
        <p>
            <?php echo $_REQUEST['new'] ?>
        </p>
    </body>
</html>