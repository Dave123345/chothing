<?php include 'db_connect.php' ?>
<?php 
    include_once './connections/connect.php'; 
    $conn = $lib->openConnection();

    $t = time();
    $in_today = strtotime("-9 hours", $t);
    $today = date("Y-m-d", $in_today);



    $today_sales = $conn->prepare("SELECT SUM(total_amount) AS total_amount FROM sales WHERE date = ?");
    $today_sales->execute([$today]);
    $count_today_sales = $today_sales->rowCount();
    if($count_today_sales > 0){
        $val = $today_sales->fetch();   
        $total_sales_today = $val['total_amount'];
    }else{
        $total_sales_today = 0;
    }

    $month = date("n");
    $month_sales = $conn->prepare("SELECT SUM(total_amount) AS total_amount FROM sales WHERE MONTH(date) = ?");

    //$month_sales = $conn->prepare("SELECT SUM(total_amount) AS total_amount FROM sales WHERE month = ?");
    $month_sales->execute([$month]);
    $count_month_sales = $month_sales->rowCount();
    if($count_month_sales > 0){
        $val = $month_sales->fetch();   
        $total_sales_month = $val['total_amount'];
    }else{
        $total_sales_month = 0;
    }

?>
<style>
   span.float-right.summary_icon {
    font-size: 3rem;
    position: absolute;
    right: 1rem;
    top: 0;
}
.imgs{
		margin: .5em;
		max-width: calc(100%);
		max-height: calc(100%);
	}
	.imgs img{
		max-width: calc(100%);
		max-height: calc(100%);
		cursor: pointer;
	}
	#imagesCarousel,#imagesCarousel .carousel-inner,#imagesCarousel .carousel-item{
		height: 60vh !important;background: black;
	}
	#imagesCarousel .carousel-item.active{
		display: flex !important;
	}
	#imagesCarousel .carousel-item-next{
		display: flex !important;
	}
	#imagesCarousel .carousel-item img{
		margin: auto;
	}
	#imagesCarousel img{
		width: auto!important;
		height: auto!important;
		max-height: calc(100%)!important;
		max-width: calc(100%)!important;
	}
</style>

<div class="containe-fluid">
	<div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <?php echo "Welcome back ". $_SESSION['login_name']."!"  ?>
                    <hr>
                    <div class="row">
                        <div class="col-md-4" style="padding: 10px">
                            <div id="box" style="box-shadow: inset 0 4px 2px #000; border-left: 10px groove #222;border-radius: 10px; background: linear-gradient(to right, #f2f2f2, #fff); padding: 10px 20px; height: 100px; ">
                                <i style="float: right;" class="ri-5x ri-currency-fill"></i>
                                <span style="font-size: 18px">Today Sales<br></span><span style="font-size: 24px">&#8369;<?= number_format($total_sales_today, 2)?></span>
                            </div>
                        </div>
                        <div class="col-md-4" style="padding: 10px">
                            <div id="box" style="box-shadow: inset 0 4px 2px #000; border-left: 10px groove #222;border-radius: 10px; background: linear-gradient(to right, #f2f2f2, #fff); padding: 10px 20px; height: 100px; ">
                                <i style="float: right;" class="ri-5x ri-currency-fill"></i>
                                <span style="font-size: 18px">Monthly Sales<br></span><span style="font-size: 24px">&#8369;<?= number_format($total_sales_month, 2)?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>      			
        </div>
    </div>
</div>
<script>
	$('#manage-records').submit(function(e){
        e.preventDefault()
        start_load()
        $.ajax({
            url:'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success:function(resp){
                resp=JSON.parse(resp)
                if(resp.status==1){
                    alert_toast("Data successfully saved",'success')
                    setTimeout(function(){
                        location.reload()
                    },800)

                }
                
            }
        })
    })
    $('#tracking_id').on('keypress',function(e){
        if(e.which == 13){
            get_person()
        }
    })
    $('#check').on('click',function(e){
            get_person()
    })
    function get_person(){
            start_load()
        $.ajax({
                url:'ajax.php?action=get_pdetails',
                method:"POST",
                data:{tracking_id : $('#tracking_id').val()},
                success:function(resp){
                    if(resp){
                        resp = JSON.parse(resp)
                        if(resp.status == 1){
                            $('#name').html(resp.name)
                            $('#address').html(resp.address)
                            $('[name="person_id"]').val(resp.id)
                            $('#details').show()
                            end_load()

                        }else if(resp.status == 2){
                            alert_toast("Unknow tracking id.",'danger');
                            end_load();
                        }
                    }
                }
            })
    }
</script>