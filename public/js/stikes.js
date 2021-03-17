$(document).ready(function () {
    $(".datepicker").datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "yy-mm-dd",
        })
        .attr('readonly', true)
        .css("background-color", "white");

    $(".select2").select2();
    $(".datepicker_agent").datepicker({
        "dateFormat": "yy-mm-dd",
        minDate: new Date(),
    });

    $(".datepicker_valid_until_date").datepicker({
            "changeMonth": true,
            "changeYear": true,
            "dateFormat": "yy-mm-dd",
            yearRange: '+0:+20',
        })
        .attr('readonly', true)
        .css("background-color", "white");

    $(".btn").click(function () {
        var $btn = $(this);
        $btn.button('loading');
        // simulating a timeout
        setTimeout(function () {
            $btn.button('reset');
        }, 1000);
    });

});

function readURL(input, image_id) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#' + image_id).attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
}

function money(num) {
  return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.')
}

function makeid(length) {
   var result           = '';
   var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
   var charactersLength = characters.length;
   for ( var i = 0; i < length; i++ ) {
      result += characters.charAt(Math.floor(Math.random() * charactersLength));
   }
   return result;
}