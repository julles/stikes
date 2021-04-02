@include("rps.partials.text_book")


<div id="tabel_or_detail"></div>

@push("js")
<script>
    function summary(){
        openTab('summary', false);
        const url = "{!! URL::to('/or/detail/'.$model['id_pm'].'/summary') !!}";
        let data  = new Object();

        var form    = new URLSearchParams(data);
        var request = new Request(url, {
            method: 'GET',
            headers: new Headers({
              'Content-Type' : 'application/x-www-form-urlencoded',
              'X-CSRF-TOKEN' : $('meta[name="csrf-token"]').attr('content')
            })
        });

        fetch(request)
        .then(response => response.json())
        .then(function(data) {
            $('#tabel_or_detail').html(data.tabel_or_detail);
        })
        .catch(function(error) {
            console.log(error);
        });
    }
</script>
@endpush