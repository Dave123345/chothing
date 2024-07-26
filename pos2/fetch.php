<?php 
                        if(isset($items)):
                           while($row=$items->fetch_assoc()):
                           ?>
    
                            <tr>
                               <td>
                                    <div class="d-flex">
                                        <span class="btn btn-sm btn-secondary btn-minus"><b><i class="fa fa-minus"></i></b></span>
                                        <input type="number" name="qty[]" id="" value="<?php echo $row['qty'] ?>">
                                        <span class="btn btn-sm btn-secondary btn-plus"><b><i class="fa fa-plus"></i></b></span>
                                    </div>
                                </td>
                                <td>
                                    <input type="hidden" name="inv_id[]" id="" value="<?php echo $row['id'] ?>">
                                    <input type="hidden" name="item_id[]" id="" value="<?php echo $row['item_id'] ?>"><?php echo ucwords($row['name']) ?>
                                    <small class="psmall"> (<?php echo number_format($row['price'],2) ?>)</small>
                                </td>
                                <td class="text-right">
                                    <input type="hidden" name="price[]" id="" value="<?php echo $row['price'] ?>">
                                    <input type="hidden" name="amount[]" id="" value="<?php echo $row['price']*$row['qty'] ?>">
                                    <span class="amount"><?php echo number_format($row['price']*$row['qty'],2) ?></span>
                                </td>
                                <td>
                                    <span class="btn btn-sm btn-danger btn-rem"><b><i class="fa fa-times text-white"></i></b></span>
                                </td>
                           </tr>
                           
                       <?php endwhile; ?>
                       
                       <?php endif; ?>
                       