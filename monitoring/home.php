

<div class="container-fluid">
    <center><button class="btn btn-lg btn-primary w-25" id="start" type="button">Start Live Queue Monitor</button></center>
    <div class="border-dark border-3 border shadow d-none" id="monitor-holder">
        <div class="row my-0 mx-0">
            <div class="col-md-5 d-flex flex-column justify-content-center align-items-center border-end border-dark" id="serving-field">

                <div class="card col-sm-12 shadow " style="height: 96% !important; margin-top: 1px;">
                    <div class="card-header">
                        <h5 class="card-title text-center" style="color: #004aad;font-weight: bold;">EN SERVICE</h5>
                    </div>
                    <div class="card-body h-100">
                        <div id="serving-list" class="list-group overflow-auto" style="height: 100%;">
                        
                            <?php 
                            $cashier = $conn->query("SELECT * FROM `cashier_list`  order by `name` asc");
                            while($row = $cashier->fetchArray()):
                            ?>
                            <div class="carda" >
                                <img src="../select2/Tlogo.png"/>
                                <div class="box" style="display:none" data-id="<?php echo $row['cashier_id'] ?>">
                                    <div class="number serve-queue" id="temp"></div>
                                    <div class="mercimsg">Merci Pour Votre Visite</div>
                                </div>
                            </div>
                            <!-- <div class="list-group-item" data-id="<?php echo $row['cashier_id'] ?>" style="display:none">
                                <div class="fs-5 fw-2 cashier-name border-bottom border-info"><?php echo $row['name'] ?></div>
                                <div class="ps-4"><span class="serve-queue fs-4 fw-bold">1001 - John Smith</span></div>
                            </div> -->
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 d-flex flex-column align-items-center bg-dark bg-gradient text-light" id="action-field" style="background: transparent !important;">
                <div style="display: flex;">
                <?php 
                    $vid = scandir('./../video');
                    $video = isset($vid[2]) ? $vid[2]: "";
                ?>
                    <video id="loop-vid" src="./../video/<?php echo $video ?>" loop class="w-100 h-100"></video>
                </div>
                <div id="datetimefield" class="w-100  col-auto">
                    <div sytle="position: relative; top: 50%">
                        <div class="fs-1 text-center time fw-bold" style="color: black; font-size: 6.5rem !important;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var websocket = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:2306/queuing/php-sockets.php"); 
    websocket.onopen = function(event) { 
      console.log('socket is open!')
		}
    websocket.onclose = function(event){
      console.log('socket has been closed!')
    var websocket = new WebSocket("ws://<?php echo $_SERVER['SERVER_NAME'] ?>:2306/queuing/php-sockets.php"); 
    };
    let tts = new SpeechSynthesisUtterance();
    tts.lang = "en"; 
    tts.voice = window.speechSynthesis.getVoices()[5] ; 
    let notif_audio = new Audio("./../audio/ascend.mp3")
    let first_audio = new Audio("./../audio/mp1.mp3")
    let second_audio = new Audio("./../audio/mp2.mp3")
    let vid_loop = $('#loop-vid')[0]
    tts.onstart= ()=>{
        vid_loop.pause()
    }
    notif_audio.setAttribute('muted',true)
    notif_audio.setAttribute('autoplay',true)
    document.querySelector('body').appendChild(notif_audio)
    first_audio.setAttribute('muted',true)
    first_audio.setAttribute('autoplay',true)
    document.querySelector('body').appendChild(first_audio)
    second_audio.setAttribute('muted',true)
    second_audio.setAttribute('autoplay',true)
    document.querySelector('body').appendChild(second_audio)
    function speak($text=""){
        if($text == '')
        return false;
        tts.text = $text; 
        notif_audio.setAttribute('muted',false)
        first_audio.setAttribute('muted',false)
        second_audio.setAttribute('muted',false)
        notif_audio.play()
        setTimeout(() => {
            setTimeout(() => {
                    first_audio.play()
                }, 500);
                setTimeout(() => {
                    window.speechSynthesis.speak(tts);
                }, 1800);
                setTimeout(() => {
                    second_audio.play()
                }, 2500);
                
           tts.onend= ()=>{
                
                vid_loop.play()
            }
        }, 500);
    }
    function time_loop(){
        var hour,min,ampm,mo,d,yr,s;
        let mos = ['','January','Febuary','March','April','May','June','July','August','September','October','November','December']
        var datetime = new Date();
        hour = datetime.getHours()
        min = datetime.getMinutes()
        s = datetime.getSeconds()
        ampm = hour >= 12 ? "PM" : "AM";
        mo = mos[datetime.getMonth()]
        d = datetime.getDay()
        yr = datetime.getFullYear()
        hour = hour >= 12 ? hour - 12 : hour;
        hour = String(hour).padStart(2,0)
        min = String(min).padStart(2,0)
        s = String(s).padStart(2,0)
        $('.time').text(hour+":"+min+":"+s+" "+ampm)
        $('.date').text(mo+" "+d+", "+yr)
            
            
    }
    function _resize_elements(){
        var window_height = $(window).height()
        var nav_height = $('nav').height()
        var container_height = window_height - nav_height
        // $('#serving-field,#action-field').height(container_height - 50)
        // $('#serving-list').height($('#serving-list').parent().height() - 30)
        $('serving-field').height($('#serving-field').parent().height() - 30)
    }

    function new_queue($cashier_id,$qid){
        $.ajax({
            url:'./../Actions.php?a=get_queue',
            method:'POST',
            data:{cashier_id:$cashier_id,qid:$qid},
            dataType:'JSON',
            error:err=>{
                console.log(err)
            },
            success:function(resp){
                if(resp.status =='success'){
                    var item = $('#serving-list').find('.box[data-id="'+$cashier_id+'"]')
                    var nitem = item.clone()
                        nitem.find('.serve-queue').text("Nº"+resp.queue)
                        item.remove()
                        $('.carda').append(nitem)
                    if(resp.queue == ''){
                        nitem.hide('slow')
                    }else{
                        nitem.show('slow')
                        speak(" "+Math.abs(resp.queue)+" ")
                    }
                }
            }
        })
    }
    $(function(){
        setInterval(() => {
            time_loop()
        }, 1000);
        $('#start').click(function(){
            $(this).hide()
            $('#monitor-holder').removeClass('d-none')
            _resize_elements()
            vid_loop.play()
        })
        $(window).resize(function(){
            _resize_elements()
        })

        websocket.onmessage = function(event) {
			var Data = JSON.parse(event.data);
            if(!!Data.type && typeof Data.type != undefined && typeof Data.type != null){
                if(Data.type == 'queue'){
                    new_queue(Data.cashier_id,Data.qid)
                }
                if(Data.type == 'test'){
                    speak("This is a sample notification.")
                }
            }
        }
    })
</script>
