var plugs;
var capacities;
var car_plugs;
var sel_car;
var sel_plug;
$(document).ready(function(){
    $.ajax({
        url: "/booking/ajax/"+sid,
        success: function(data){
            jsonData=JSON.parse(data);
            plugs=jsonData['plugs'];
            capacities=jsonData['capacities'];
            car_plugs=jsonData['carplugs'];
            // alert('yoohoo');
            sel_car=document.getElementById('booking_car');
            sel_plug=document.getElementById('booking_plug');
            calculateDuration();
            checkCompatibility();

        },
        error: function(data){
        }
    });
});
$(document).on('change',['#booking_car','#booking_duration'],function() {
    checkCompatibility();
});

function calculateDuration(){
    var plate=sel_car.options[sel_car.selectedIndex].text;
    var plug=sel_plug.options[sel_plug.selectedIndex].text;
    var plug_id= plug.substring(0, plug.indexOf('.'));
    var battery_input=document.getElementById('booking_battery');
    var battery=battery_input.value;
    var duration=document.getElementById('booking_duration');

    if(battery==null || battery<0){
        battery=30;
        battery_input.value=30;
    }
    // CALCULATE DURATION
    // alert(battery);
    if(capacities!=null && plugs!=null){
        if(capacities[plate]==null)
            duration.value=(8*60)-(battery/100)*(8*60);
        else{
             var timecalc=(capacities[plate]/plugs[plug_id])*60;
            // TC = TIME CALCULATED, T = TOTAL TIME, B = BATTERY
            // FORMULA: T=TC-(B/100)*TC
            duration.value=Math.ceil(timecalc-(battery/100)*timecalc);
        }
    }
}
function checkCompatibility(){
    var plate=sel_car.options[sel_car.selectedIndex].text;
    var plug=sel_plug.options[sel_plug.selectedIndex].text;
    var plug_name=plug.substring(plug.indexOf('.') + 2);
    console.log(plug_name);
    if(car_plugs[plate].toLowerCase()!==plug_name.toLowerCase()){
        if($('#plug_warn','#warning').length === 0){
            $('#warning').append('<p style="color:orange;"id="plug_warn">Warning: the current selected plate and plug are incompatible. Continue at your own risk.</p>')
        }
    }
    else{
        $('#warning p').remove();
    }

}

function checkTimeRange(){
    var sel_time=document.getElementById('booking_start_time');
    var time=sel_time.value;
    $.ajax({
        url: "/booking/availableplugs/",
        data:{
            time:time,
            sid:sid,
        },
        success: function(data){
            jsonData=JSON.parse(data);

        },
        error: function(data){
        }
    });

}
