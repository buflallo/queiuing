<?php
require_once('./DBConnection.php');
if(isset($_GET['id'])){
    $qry = $conn->query("SELECT * FROM `queue_list` where queue_id = '{$_GET['id']}'");
    @$res = $qry->fetchArray();
    if($res){
        foreach($res as $k => $v){
            if(!is_numeric($k)){
                $$k = $v;
            }
        }
    }
}
?>
<link href='https://fonts.googleapis.com/css?family=Montserrat:700' rel='stylesheet' type='text/css'>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
    img{
            width: 303px;
            height: auto;
    }
    .number {
        color: black;
        font-family: 'Montserrat', sans-serif;
        font-weight: 900;
        font-size: 70px;
        text-align: center;
    }

    .mercimsg{
        color: black;
        font-family: 'Montserrat', sans-serif;
        font-weight: 700;
        font-size: 16px;
        text-align: center;
        
    }
    .box{
        color: #004aad !important;
    }
    .carda {
        width: 303px;
        height: 280px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }
</style>
<div class="container fluid">
    <?php if(isset($_GET['success']) && $_GET['success'] == true): ?>
        <div class="alert alert-success">Le ticket a été généré avec succès.</div>
    <?php endif; ?>

    <div id="outprint" class="carda">
        <style>
            @media print{
                #uni_modal .modal-footer{
                    display:none;
                }
                img{
                    width: 303px;
                    height: 125px;
                }
                .number {
                    color: black;
                    font-family: 'Montserrat', sans-serif;
                    font-weight: 900;
                    font-size: 70px;
                    text-align: center;
                }

                .mercimsg{
                    color: black;
                    font-family: 'Montserrat', sans-serif;
                    font-weight: 700;
                    font-size: 16px;
                    text-align: center;
                    
                }
                .carda {
                    width: 303px;
                    height: 280px;
                    margin: 0 auto;
                    background: white;
                    box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
                }
            }
        </style>
        <img src="../select2/Tlogo.png"/>
        <div class="box">
            <div class="number" id="temp">Nº<?php echo $queue ?></div>
            <div class="mercimsg">Merci Pour Votre Visit</div>
            <div class="mercimsg"><?php echo date("H:i:sa") ?></div>
        </div>
    </div>
    <div class="row my-2 mx-0 justify-content-end align-items-center">
        <button class="btn btn-success rounded-0 me-2 col-sm-4" id="print" type="button"><i class="fa fa-print"></i> Print</button>
        <button class="btn btn-dark rounded-0 col-sm-4" data-bs-dismiss="modal" type="button"><i class="fa fa-times"></i> Close</button>
    </div>
</div>
<script>
    $(function(){
        $('#print').click(function(){
            var _el = $('<div>')
            var _h = $('head').clone()
            var _p = $('#outprint').clone()
            _h.find('title').text("Queue Number - Print")
            _el.append(_h)
            _el.append(_p)
            var nw = window.open('','_blank','width=700,height=280,top=75,left=200')
                nw.document.write(_el.html())
                nw.document.close()
                setTimeout(() => {
                    nw.print()
                    setTimeout(() => {
                        nw.close()
                        $('#uni_modal').modal('hide')
                    }, 200);
                }, 500);
        })
    })
</script>