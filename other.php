<?php 

include_once 'connections/connect.php';
$con = $lib->openConnection();



        $items = $con->prepare("SELECT * FROM cart AS c INNER JOIN items AS i ON c.item_id = i.id order by c.id desc");
        $items->execute();

        while($row=$items->fetch()){

                ?>
            <tr>
                <th><center><button class="btn btn-danger"><i class="fa fa-trash"></i></button></center></th>
                <td><?= $row['name'] ?></td>
                <td><?= $row['item_code'] ?></td>
                <td><?= $row['price'] ?></td>
                <td style="text-align: center">
                <button class="btn btn-success btn-sm add"><i class="fa fa-plus"></i></button>
                    <input type="number" style="text-align: center; width: 40px; margin-top: 10px" value="1" min="0">
                <button class="btn btn-success btn-sm subtract"><i class="fa fa-minus"></i></button></td>
            </tr>
                <?php


        }





?>