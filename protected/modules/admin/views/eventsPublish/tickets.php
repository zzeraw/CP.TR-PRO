<h1><?php echo $event->title; ?></h1>
Дата: <?php echo Helper::formatDate($event->eventDate); ?>
<br><br>
<table class="tickets">
<?php
$rowsAmount = count($codes);
for ($i = 0; $i < $rowsAmount; ++$i)
{
    echo '<tr>';
    for ($j = 0; $j < 4; ++$j)
    {
        echo '<td>'.$codes[$i][$j].'</td>';
    }
    echo '</tr>';
}
?>
</table>