<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card rouded-0 shadow">
                <div class="card-header rounded-0">
                    <div class="h5 card-title">Gérer La liste des tickets ici.</div>
                </div>
                <div class="card-body rounded-0 form-group text-center form-group text-center w-100 row row-cols-sm-2 row-cols-md-2 row-cols-xl-2 row g-4">
                    <form class="col" action="" id="queue-form">
                        <div class="">
                            <div class="">
                                <button class="btn btn-flat btn-primary rounded-0 btn-lg" type='submit'>Génerer tickets</button>
                            </div>
                        </div>
                    </form>
                    <form class="col" action="" id="reset-form">
                        <div class="">
                            <div class="">
                                <button class="btn btn-flat btn-secondary rounded-0 btn-lg" type='submit'>Réinitialiser La liste</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $('#queue-form').submit(function(e){
        e.preventDefault()
        var _this = $(this)
        _this.find('.pop-msg').remove()
        var el = $('<div>')
        el.addClass('alert pop-msg')
        el.hide()
        _this.find('button[type="submit"]').attr('disabled',true)
        console.log(_this.serialize())
        $.ajax({
            url:'../Actions.php?a=save_queue',
            method:'POST',
            data:_this.serialize(),
            dataType:'JSON',
            error:err=>{
                // console.log(err)
                el.addClass("alert-danger")
                el.text("An error occured while saving data.")
                _this.find('button[type="submit"]').attr('disabled',false)
                _this.prepend(el)
                el.show('slow')
            },
            success:function(resp){
                if(resp.status == 'success'){
                    // console.log(resp.id)
                    uni_modal("Création de ticket","../get_queue.php?success=true&id="+resp.id)
                    $('#uni_modal').on('hide.bs.modal',function(e){
                        location.reload()
                    })
                }else if(resp.status ='failed' && !!resp.msg){
                    el.addClass('alert-'+resp.status)
                    el.text(resp.msg)
                    _this.prepend(el)
                    el.show('slow')
                }else{
                    el.addClass('alert-'+resp.status)
                    el.text("An Error occured.")
                    _this.prepend(el)
                    el.show('slow')
                }
                _this.find('button[type="submit"]').attr('disabled',false)
            }
        })
    })
})
$(function(){
    $('#reset-form').submit(function(e){
        e.preventDefault()
        var _this = $(this)
        _this.find('.pop-msg').remove()
        var el = $('<div>')
        el.addClass('alert pop-msg')
        el.hide()
        _this.find('button[type="submit"]').attr('disabled',true)
        console.log(_this.serialize())
        $.ajax({
            url:'../Actions.php?a=reset_queue',
            method:'POST',
            data:_this.serialize(),
            dataType:'JSON',
            error:err=>{
                console.log(err)
                el.addClass("alert-danger")
                el.text("An error occured while saving data.")
                _this.find('button[type="submit"]').attr('disabled',false)
                _this.prepend(el)
                el.show('slow')
            },
            success:function(resp){
                if(resp.status == 'success'){
                    console.log("resete success")
                    $('#uni_modal .modal-title').html("réinitialisée avec succès")
                    $('#uni_modal .modal-body').html("<div class='text-center'><h4>La liste a été réinitialisée avec succès.</h4></div>")
                    $('#uni_modal .modal-dialog').removeClass('large')
                    $('#uni_modal .modal-dialog').removeClass('mid-large')
                    $('#uni_modal .modal-dialog').removeClass('modal-md')
                    $('#uni_modal .modal-dialog').addClass('modal-md')
                    $('#uni_modal').modal({
                        backdrop: 'static',
                        keyboard: true,
                        focus: true
                    })
                    $('#uni_modal').modal('show')
                }else if(resp.status ='failed' && !!resp.msg){
                    el.addClass('alert-'+resp.status)
                    el.text(resp.msg)
                    _this.prepend(el)
                    el.show('slow')
                }else{
                    el.addClass('alert-'+resp.status)
                    el.text("An Error occured.")
                    _this.prepend(el)
                    el.show('slow')
                }
                _this.find('button[type="submit"]').attr('disabled',false)
            }
        })
    })
})

</script>