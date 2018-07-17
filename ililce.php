<?php
date_default_timezone_set('Europe/Istanbul');
setlocale(LC_ALL, 'tr_TR.UTF-8', 'tr_TR', 'tr', 'turkish');

require_once("class.php");
$ep= new epclass();
$iladi="Ankara";
$ilceadi="Keçiören";
$ililce= $ep->ililce($iladi,$ilceadi);

?>

<h1><?php echo $iladi; ?> | <?php echo $ilceadi; ?></h1><br>
<style>
table {
    font-family: arial, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

td, th {
    border: 1px solid #dddddd;
    text-align: left;
    padding: 8px;
}

tr:nth-child(even) {
    background-color: #ffffff;
}
</style>

<table>
  <tr>
    <th>Tarih</th>
    <th>Hadise</th>
    <th>Sıcaklık (En Yüksek - En Düşük)</th>
    <th>Nem (En Yüksek - En Düşük)</th>
    <th>Rüzgar (Hız - Yön)</th>
  </tr>
  <?php
  for($i=0; $i<count($ililce); $i++){
    $gun=$ililce[$i];
    $tarih= $gun["tarih"];
    $tarih= iconv("ISO-8859-9","UTF-8",strftime("%d %B %A", strtotime($tarih)));

    ?>
    <tr>
      <td><?php echo $tarih; ?></td>
      <td><img src="https://mgm.gov.tr/Images_Sys/hadiseler/<?php echo $gun["hadise"]; ?>.svg"></td>
      <td><?php echo $gun["sicaklik"]["yuksek"]; ?> - <?php echo $gun["sicaklik"]["dusuk"]; ?></td>
      <td><?php echo $gun["nem"]["yuksek"]; ?> - <?php echo $gun["nem"]["dusuk"]; ?></td>
      <td><?php echo $gun["ruzgar"]["hiz"]; ?> - <img src="https://mgm.gov.tr/Images_Sys/main_page/ryon-gri.svg" style="transform: rotate(<?php echo $gun["ruzgar"]["yon"]; ?>deg);"></td>
    </tr>
    <?php
  }
?>
</table>
