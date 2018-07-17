<?php
date_default_timezone_set('Europe/Istanbul');
setlocale(LC_ALL, 'tr_TR.UTF-8', 'tr_TR', 'tr', 'turkish');

require_once("class.php");
$ep= new epclass();
$iller= $ep->iller();

if($_GET["il"]!=""){

  if(isset($_GET["il"])){
  $ilceler= $ep->ilceler($_GET["il"]);
  echo json_encode($ilceler,true);

  }

  exit;
}

?>

<select name="iller">
  <option value="" style="display:none;">Lütfen il seçiniz</option>
  <?php
  for($i=0; $i<count($iller); $i++){
    if($iller[$i]!=""){
    ?>
    <option value="<?php echo $iller[$i][0]; ?>"><?php echo $iller[$i][1]; ?></option>
    <?php
    }
  }
  ?>
</select>

<select name="ilceler" style="display:none;">

</select>
<br>
<a href="" id="button"><button type="submit"> Gönder </button></a>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script>
$(document).ready(function(){
  $("select[name=iller]").change(function(){
    $("select[name=iller] option:selected").each(function() {
      var il=$(this).text();
      var ilid=$(this).val();
        $.ajax({
            type: 'POST',
            url: "genel.php?il="+il,
            success: function ( result, status, xhr ) {
              console.log(xhr.responseText);
              var json= JSON.parse(xhr.responseText);
              $("select[name=ilceler]").html("");
              $("select[name=ilceler]").append('<option value="" style="display:none;" selected>Lütfen ilce seçiniz</option>');
              $("select[name=ilceler]").show();
              $("button").text("İl Getir");
              $("#button").attr("href","gunluk.php?id="+ilid);
              for(var i=0; i<json.length; i++ ){
                $("select[name=ilceler]").append("<option value='"+json[i][1]+"'>"+json[i][0]+"</option>");
              };

            },
        });
    });
  });

  $("select[name=ilceler]").change(function(){
    $("select[name=ilceler] option:selected").each(function() {
      var ilce=$(this).val();
      $("button").text("İlçe Getir");
      $("#button").attr("href","gunluk.php?id="+ilce);
    });
  })
})
</script>
