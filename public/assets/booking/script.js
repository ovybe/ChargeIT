function calculateDuration(){
    var plugs;
    var capacities;
    var sel_car=document.getElementById('booking_car');
    var plate=sel_car.options[sel_car.selectedIndex].text;
    var sel_plug=document.getElementById('booking_plug');
    var plug=sel_plug.options[sel_plug.selectedIndex].text;
    var plug_id= plug.substring(0, plug.indexOf('.'));
    var battery_input=document.getElementById('booking_battery');
    var battery=battery_input.value;
    var duration=document.getElementById('booking_duration');
    $.ajax({
        url: "/booking/ajax/"+sid,
        success: function(data){
            jsonData=JSON.parse(data);
            plugs=jsonData['plugs'];
            capacities=jsonData['capacities'];
            if(battery==null)
                battery=30;
            // CALCULATE DURATION
            // alert(battery);
            if(capacities[plate]==null)
                duration.value=(8*60)-(battery/100)*(8*60);
            else{
                var timecalc=(capacities[plate]/plugs[plug_id])*60;
                // TC = TIME CALCULATED, T = TOTAL TIME, B = BATTERY
                // FORMULA: T=TC-(B/100)*TC
                duration.value=Math.ceil(timecalc-(battery/100)*timecalc);
            }
        },
        error: function(data){
            alert("Estimation failed! Assuming average car charging time.");
            duration.value=(8*60)-(battery/100)*(8*60);
        }
    });



}