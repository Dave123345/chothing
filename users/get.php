<?php
session_start();
include_once '../connections/connect.php';
$con = $lib->openConnection();


if(isset($_POST['code'])):

     $check_code = $con->prepare("SELECT * FROM items WHERE item_code = ?");
     $check_code->execute([$_POST['code']]);

    if($check_code->rowCount() > 0){

        $val = $check_code->fetch();

        $check_cart = $con->prepare("SELECT * FROM cart WHERE item_id = ?");
        $check_cart->execute([$val['id']]);

        if($check_cart->rowCount() > 0){

            echo 'ok';

        }else{

            $ins = $con->prepare("INSERT INTO cart (`item_id`) VALUES (?)");
            $ins->execute([$val['id']]);
    
            echo 'ok';
        }


    }else{

        $_SESSION['code'] = $_POST['code'];

        $check_code = $con->prepare("SELECT * FROM added_prod WHERE id = ?");
        $check_code->execute([1]);

        if($check_code->rowCount() > 0){

            $upd = $con->prepare("UPDATE added_prod SET code = ? WHERE id = ?");
            $upd->execute([$_POST['code'], 1]);

        }else{

            $ins = $con->prepare("INSERT INTO added_prod (`code`) VALUES (?)");
            $ins->execute([$_POST['code']]);
        }
        
        
        echo $_POST['code'];

    }


endif;
?>