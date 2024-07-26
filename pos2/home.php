<?php 
include '../db_connect.php';
include_once '../connections/connect.php';
$con = $lib->openConnection();



?>

<style>
    body{
        background: linear-gradient(to right, #ddd, #fff, #ddd);
    }
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
    }
    .bg-gradient-primary{
        background: rgb(119,172,233);
        background: linear-gradient(149deg, rgba(119,172,233,1) 5%, rgba(83,163,255,1) 10%, rgba(46,51,227,1) 41%, rgba(40,51,218,1) 61%, rgba(75,158,255,1) 93%, rgba(124,172,227,1) 98%);
    }
    .btn-primary-gradient{
        background: linear-gradient(to right, #1e85ff 0%, #00a5fa 80%, #00e2fa 100%);
    }
    .btn-danger-gradient{
        background: linear-gradient(to right, #f25858 7%, #ff7840 50%, #ff5140 105%);
    }
    main .card{
        height:calc(100%);
    }
    main .card-body{
        height:calc(00%);
        overflow: auto;
        padding: 5px;
        position: relative;
    }
    main .container-fluid, main .container-fluid>.row,main .container-fluid>.row>div{
        height:calc(100%);
    }
    #o-list{
        height: calc(87%);
        overflow: auto;
    }
    #calc{
        position: absolute;
        bottom: 1rem;
        height: calc(10%);
        width: calc(98%);
    }
    .prod-item{
        min-height: 12vh;
        cursor: pointer;
    }
    .prod-item:hover{
        opacity: .8;
    }
    .prod-item .card-body {
        display: flex;
        justify-content: center;
        align-items: center;

    }
    input[name="qty[]"]{
        width: 30px;
        text-align: center
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }
    #cat-list{
        height: calc(100%)
    }
    .cat-item{
        cursor: pointer;
    }
    .cat-item:hover{
        opacity: .8;
    }
    #p-list tbody tr{
      cursor: pointer
    }
</style>


<?php 
if(isset($_GET['id'])):


// $check_code = $con->prepare("SELECT * FROM items WHERE item_code = ?");
// $check_code->execute([]);

// $sale = $conn->query("SELECT * FROM sales where id = {$_GET['id']}");
// foreach($sale->fetch_array() as $k => $v){
//     $$k= $v;
// }

$items = $conn->query("SELECT * FROM cart AS c INNER JOIN items AS I ON c.item_id = i.id");


endif;
?>
<div class="container-fluid o-field">
	<div class="row mt-3 ml-1 mr-1">
        <div class="col-lg-2">
<!--            <div class="card bg-dark border-primary" style=" margin-bottom: 30px">
               <div class="card-body"  style="display: none; height: 59vh;">
                <form action="" id="manage-order" style="background: red">
                    <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
                    <div class="bg-white" id='o-list'>
                    <table class="table table-bordered bg-light" >
                            <colgroup>
                                <col width="20%">
                                <col width="40%">
                                <col width="40%">
                                <col width="5%">
                            </colgroup>
                        <thead>
                            <tr>
                                <th>QTY</th>
                                <th>Order</th>
                                <th>Amount</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="content">
    
                        <script>
                                $(document).ready(function(){
                                    qty_func()
                                        calc()
                                        cat_func();
                                })
                            </script>
                        </tbody>
                    </table>
                    </div>
                   <div class="d-block bg-white" id="calc">
                       <table class="" width="100%">
                           <tbody>
                                <tr>
                                   <td><b><h4>Total</h4></b></td>
                                   <td class="text-right">
                                       <input type="hidden" name="total_amount" value="0">
                                       <input type="hidden" name="total_tendered" value="0">
                                       <span class=""><h4><b id="total_amount">0.00</b></h4></span>
                                   </td>
                               </tr>
                           </tbody>
                       </table>
                   </div>
                </form>
               </div>
           </div> -->
        </div>
        <div class="col-lg-8  p-field">
        <h2 style="font-weight: bolder; text-shadow: 1px 2px grey;"><center>Cartculator</center></h2>
        <form method="POST">
            <div class="card" style="background: #fff; height: 70vh; border: 10px groove #f2f2f2;" >
                <div class="card-header bg-dark text-white  border-primary">
                    <b>Products</b>
                </div>
                <div class="card-body d-flex" id='prod-list'>
                    <div class="col-md-12 h-100 bg-white" style="overflow: scroll;box-shadow: inset 0px 4px 10px #222, inset 0px 4px 20px #222;">
                        <hr>
                        <div class="d-flex w-100 mb-2">
                            <label for="" class="text-dark col-sm-2"><b>Search</b></label>
                            <input type="text" class="form-control form-control-sm col-sm-10" id="filter">
                        </div>
                        <table class="table table-bordered table-hover bg-white" id="p-list">
                          <thead>
                            <tr>
                              <th class="text-center">#</th>
                              <!-- <th class="text-center">Item Code</th> -->
                              <th class="text-center">Item Name </th>
                              <!-- <th class="text-center">Item Size</th> -->
                              <th class="text-center">Price</th>
                              <th class="text-center">Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              $total = 0;
                              $i = 1;
                              $items = $conn->query("SELECT *, c.id, c.item_id FROM cart AS c INNER JOIN items AS I ON c.item_id = i.id");
                              while($row=$items->fetch_assoc()):
                            ?>
                            <tr data-id="<?php echo $row['id'] ?>" class="p-item" data-json='<?php echo json_encode($row) ?>'>
                            <td class="text-center">
                                <?php echo $i++; ?>
                            </td>

                            
                           <input type="hidden" id="item_code[]" name="item_code[]" value="<?= $row['item_code']; ?>">
                           <input type="hidden" id="item_id" name="item_id" value="<?= $row['item_id']; ?>">
                              <!-- <td><b><?php echo $row['item_code'] ?></b></td> -->
                            <td class="text-center">
                                <b><?php echo ucwords($row['name']) ?></b>
                                <input type="hidden" name="name" value="<?= $row['name']; ?>">
                            </td>
                              <!-- <td><b><?php echo $row['size'] ?></b></td> -->
                              <td class="text-center">
                                <b><?php echo number_format($row['price'],2) ?></b>

                              </td>
                              <td class="text-center"><a href="remove.php?pid=<?=$row['id'];?>" class="btn btn-danger"><i class="fa fa-trash"></i></a></td>
                           </tr>
                            <?php $total += $row['price']; ?>
                            <?php endwhile; ?>
                            
                           <input type="hidden" name="pricetot" id="pricetot" value="<?= $total; ?>">
                          </tbody>
                        </table>
                    </div>   
                </div>
            <div class="card-footer bg-dark border-primary">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <p style="float: right; text-align: right; color: #f2f2f2; font-size: 24px">Total: &#8369;<?= number_format($total, 2); ?></p>
                    </div>
                    
                    
                    <div id="paypal-button-container"></div>
                    <button style="width: 150px" class="btn btn-sm btn-warning" onclick="window.alert('Proceed To Counter.')">Cash</button>
                    <!-- <button class="btn btn btn-sm col-sm-3 btn-primary mr-2" type="button" id="pay">Pay</button> -->
                </div>
            </div>
            </div>      			
        </form>
        </div>
        <div class="col-lg-2"></div>
    </div>
</div>
<div class="modal fade" id="pay_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
      <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title"><b>Pay</b></h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
            <select name="opt" id="opt" class="form-control">
                <option> Choose payment method </option>
                <option value="cash"> Cash </option>
                <option value="paypal"> Paypal </option>
            </select>
            <br><br>
            <div id="cash" style="display: none">
            <div class="form-group">
                <label for="">Amount Payable</label>
                <input type="text" class="form-control text-right" id="apayable" readonly="" value="">
            </div>
            <div class="form-group">
                <label for="">Amount Tendered</label>
                <input type="text" class="form-control text-right" id="tendered" value="" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="">Change</label>
                <input type="text" class="form-control text-right" id="change" value="0.00" readonly="">
            </div>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary btn-sm"  form="manage-order">Pay</button>
        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
      </div>
      </div>
    </div>
  </div>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery.session@1.0.0/jquery.session.min.js"></script>
  <script src="https://www.paypal.com/sdk/js?client-id=AWRPB1FcWXE10COy90wsn035crDUvdhdggcJENVQ4vG5iJsj2bYjvU4IQZmio3Vz72lGVODiM7NRMzIh&currency=PHP"></script>
  
  <script>
            var amount_pay = document.getElementById("pricetot").value;
            var item_ids = document.getElementById("item_id").value;

            console.log(amount_pay);


            paypal.Buttons({
                style: {
                    layout: 'horizontal',
                    color:  'blue',
                    shape:  'pill',
                    label:  'paypal',
                    tagline: false
                },
                createOrder: function(data, actions){
                    return actions.order.create({
                        purchase_units: [
                            {
                                amount: {
                                    value: parseFloat(amount_pay).toFixed(2)
                                }
                            }
                        ],
                    });

                },
                onApprove: function(data, actions){
                    return actions.order.capture().then(function(details){
                        var payer = details.payer.name.given_name + " " + details.payer.name.surname;
                        var status = details.status;
                        var t_id = details.id;
                        

                        alert ('Payment Successful!');
                        location.href = "success.php?t_id="+t_id+"&total_amount="+amount_pay;
                    
                    });

                },
                onCancel: function (data) {
                    // Show a cancel page, or return to cart
                }
            }).render('#paypal-button-container');

        </script>
  <script>
		$(document).ready(function(){
			$("#content").load("fetch.php");

            $('#opt').on('change', function(){
                if($(this).val() == 'cash'){

           
                    $('#cash').show();
                    $('#paypal-button-container').hide();
                    $('.modal-footer').show();
                     
                }

                if($(this).val() == 'paypal'){

                    $('#cash').hide();
                    $('#paypal-button-container').show();
                    $('.modal-footer').hide();
 
                }


            })
		})
	</script>
    
<script>


    var total;
    cat_func();
   $('#p-list .p-item').click(function(){
        var data = $(this).attr('data-json')
            data = JSON.parse(data)
        if($('#o-list tr[data-id="'+data.id+'"]').length > 0){
            var tr = $('#o-list tr[data-id="'+data.id+'"]')
            var qty = tr.find('[name="qty[]"]').val();
                qty = parseInt(qty) + 1;
                qty = tr.find('[name="qty[]"]').val(qty).trigger('change')
                calc()
            return false;
        }
        var tr = $('<tr class="o-item"></tr>')
        tr.attr('data-id',data.id)
        tr.append('<td><div class="d-flex"><span class="btn btn-sm btn-secondary btn-minus"><b><i class="fa fa-minus"></i></b></span><input type="number" name="qty[]" id="" value="1"><span class="btn btn-sm btn-secondary btn-plus"><b><i class="fa fa-plus"></i></b></span></div></td>') 
        tr.append('<td><input type="hidden" name="inv_id[]" id="" value=""><input type="hidden" name="item_id[]" id="" value="'+data.id+'">'+data.name+' <small class="psmall">('+(parseFloat(data.price).toLocaleString("en-US",{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))+')</small></td>') 
        tr.append('<td class="text-right"><input type="hidden" name="price[]" id="" value="'+data.price+'"><input type="hidden" name="amount[]" id="" value="'+data.price+'"><span class="amount">'+(parseFloat(data.price).toLocaleString("en-US",{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))+'</span></td>') 
        tr.append('<td><span class="btn btn-sm btn-danger btn-rem"><b><i class="fa fa-times text-white"></i></b></span></td>')
        $('#o-list tbody').append(tr)
        qty_func()
        calc()
        cat_func();
   })
    function qty_func(){
         $('#o-list .btn-minus').each(function(){
            $(this).click(function(e){
                e.preventDefault()
                var qty = $(this).siblings('input').val()
                    qty = qty > 1 ? parseInt(qty) - 1 : 1;
                    $(this).siblings('input').val(qty).trigger('change')
                    calc()
            })
         })
         $('#o-list .btn-plus').each(function(e){
            $(this).click(function(e){
                e.preventDefault()
                var qty = $(this).siblings('input').val()
                    qty = parseInt(qty) + 1;
                    $(this).siblings('input').val(qty).trigger('change')
                    calc()
            })
         })
         $('#o-list .btn-rem').each(function(e){
            $(this).click(function(e){
                e.preventDefault()
                $(this).closest('tr').remove()
                calc()
            })
         })
         
    }
    function calc(){
         $('[name="qty[]"]').each(function(){
            $(this).change(function(){
                var tr = $(this).closest('tr');
                var qty = $(this).val();
                var price = tr.find('[name="price[]"]').val()
                var amount = parseFloat(qty) * parseFloat(price);
                    tr.find('[name="amount[]"]').val(amount)
                    tr.find('.amount').text(parseFloat(amount).toLocaleString("en-US",{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
                
            })
         })
         var total = 0;
         $('[name="amount[]"]').each(function(){
            total = parseFloat(total) + parseFloat($(this).val()) 
         })
        $('[name="total_amount"]').val(total)
        $('#total_amount').text(parseFloat(total).toLocaleString("en-US",{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
    }
   function cat_func(){
    $('.cat-item').click(function(){
            var id = $(this).attr('data-id')
            console.log(id)
            if(id == 'all'){
                $('.prod-item').parent().toggle(true)
            }else{
                $('.prod-item').each(function(){
                    if($(this).attr('data-category-id') == id){
                        $(this).parent().toggle(true)
                    }else{
                        $(this).parent().toggle(false)
                    }
                })
            }
    })
   }
   $('#save_order').click(function(){
    $('#tendered').val('').trigger('change')
    $('[name="total_tendered"]').val('')
    $('#manage-order').submit()
   })
   $("#pay").click(function(){
    start_load()
    var amount = $('[name="total_amount"]').val()
    
    sessionStorage.setItem('tot_amount',amount);

    if($('#p-list tbody tr').length <= 0){
        alert_toast("Please add atleast 1 product first.",'danger')
        end_load()
        return false;
    }
    $('#apayable').val(parseFloat(amount).toLocaleString("en-US",{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
    $('#pay_modal').modal('show')
    setTimeout(function(){
        $('#tendered').val('').trigger('change')
        $('#tendered').focus()
        end_load()
    },500)
    
   })
   $('#tendered').keyup('input',function(e){
        if(e.which == 13){
            $('#manage-order').submit();
            return false;
        }
        var tend = $(this).val()
            tend =tend.replace(/,/g,'') 
        $('[name="total_tendered"]').val(tend)
        if(tend == '')
            $(this).val('')
        else
            $(this).val((parseFloat(tend).toLocaleString("en-US")))
        tend = tend > 0 ? tend : 0;
        var amount=$('[name="total_amount"]').val()
        var change = parseFloat(tend) - parseFloat(amount)
        $('#change').val(parseFloat(change).toLocaleString("en-US",{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
   })
   
    $('#tendered').on('input',function(){
        var val = $(this).val()
        val = val.replace(/[^0-9 \,]/, '');
        $(this).val(val)
    })


    $('#manage-order').submit(function(e){
        e.preventDefault();
        start_load()
        $.ajax({
            url:'../ajax.php?action=save_order',
            method:'POST',
            data:$(this).serialize(),
            success:function(resp){

                
                console.log(resp);

                if(resp > 0){

                    if($('[name="total_tendered"]').val() >= $('[name="total_amount"]').val()){

                        alert_toast("Data successfully saved.",'success')
                        setTimeout(function(){
                            var nw = window.open('../receipt.php?id='+resp,"_blank","width=900,height=600")
                            setTimeout(function(){
                                nw.print()
                                setTimeout(function(){
                                    nw.close()
                                    location.reload()
                                },500)
                            },500)
                        },500)
                    }else{
                        alert_toast("Data successfully saved.",'success')
                        setTimeout(function(){
                            location.reload()
                        },500)
                    }
                }else{
                        // alert_toast("Insuffiecient amount.",'danger');
                        
                        alert_toast(resp,'danger');
                        
                    // alert(resp);

                        setTimeout(function(){
                            location.reload()
                        },500)

                }
            }
        })
    });





    $('#filter').keyup(function(){
      var filter = $(this).val()
        $('.p-item').each(function(){
          var txt = $(this).text();
          if((txt.toLowerCase()).includes(filter.toLowerCase())==true){
            $(this).toggle(true)
          }else{
            $(this).toggle(false)
          }
        })
    })
</script>
