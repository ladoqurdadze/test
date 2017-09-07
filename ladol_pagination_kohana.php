globalurad shecvlili maqvs: 

return array(

	// Application defaults
	'default' => array(
		'current_page'      => array('source' => 'query_string', 'key' => 'page'), // source: "query_string" or "route"
		'total_items'       => 0,
		'items_per_page'    => 10,
//		'view'              => 'pagination/floating',
		'view'              => 'pagination/ladol',
		'auto_hide'         => TRUE,
		'first_page_in_url' => FALSE,
	),

);

amit magram me shevcvli konkretuls;  












// ======================   ეს მოდულებში ფაგინაციაში ,  ვიუში მაქვს 

<?php
/*
	First Previous 1 2 3 ... 22 23 24 25 26 [27] 28 29 30 31 32 ... 48 49 50 Next Last
*/

// Number of page links in the begin and end of whole range
$count_out = ( ! empty($config['count_out'])) ? (int) $config['count_out'] : 1;
// Number of page links on each side of current page
$count_in = ( ! empty($config['count_in'])) ? (int) $config['count_in'] : 5;

// Beginning group of pages: $n1...$n2
$n1 = 1;
$n2 = min($count_out, $total_pages);

// Ending group of pages: $n7...$n8
$n7 = max(1, $total_pages - $count_out + 1);
$n8 = $total_pages;

// Middle group of pages: $n4...$n5
$n4 = max($n2 + 1, $current_page - $count_in);
$n5 = min($n7 - 1, $current_page + $count_in);
$use_middle = ($n5 >= $n4);

// Point $n3 between $n2 and $n4
$n3 = (int) (($n2 + $n4) / 2);
$use_n3 = ($use_middle && (($n4 - $n2) > 1));

// Point $n6 between $n5 and $n7
$n6 = (int) (($n5 + $n7) / 2);
$use_n6 = ($use_middle && (($n7 - $n5) > 1));

// Links to display as array(page => content)
$links = array();

// Generate links data in accordance with calculated numbers
for ($i = $n1; $i <= $n2; $i++)
{
    $links[$i] = $i;
}
if ($use_n3)
{
    $links[$n3] = '&hellip;';
}
for ($i = $n4; $i <= $n5; $i++)
{
    $links[$i] = $i;
}
if ($use_n6)
{
    $links[$n6] = '&hellip;';
}
for ($i = $n7; $i <= $n8; $i++)
{
    $links[$i] = $i;
}




// Click on Prev And Next Page Link Page must Change By 10 num
if( $previous_page !== false && ($previous_page - 10) > 0 ){
    $previous_page -= 9;
}
if( $next_page !== false && ($next_page + 10) < $total_pages ){
    $next_page += 9;
}



?>
<div class="lado-pag-kohana-l" >
    <?php if ($first_page !== FALSE): ?>
        <span class='lado-pag-item l-p-prev-items l-p-item-first'><a href="<?php echo HTML::chars($page->url($first_page)) ?>" rel="first"><?php echo __('First') ?></a></span>
    <?php else: ?>
        <span class='lado-pag-item l-p-prev-items lado-pag-item-without-link l-p-item-first'><?php echo __('First') ?></span>
    <?php endif ?>

    <?php if ($previous_page !== FALSE): ?>
        <span class='lado-pag-item l-p-prev-items'><a href="<?php echo HTML::chars($page->url($previous_page)) ?>" rel="prev"><?php echo __('Previous') ?></a></span>
    <?php else: ?>
        <span class='lado-pag-item l-p-prev-items lado-pag-item-without-link'><?php echo __('Previous') ?></span>
    <?php endif ?>

    <?php foreach ($links as $number => $content): ?>
        <?php // if( $content != '&hellip;' && ( $total_pages - 5 > $current_page ) && $number < $current_page ){  continue; } ?>
        <?php if ($number === $current_page): ?>
            <span class='lado-pag-item l-p-loop-nums l-p-item-sel'><?php echo $content ?></span>
        <?php else: ?>
            <span class='lado-pag-item l-p-loop-nums'><a href="<?php echo HTML::chars($page->url($number)) ?>"><?php echo $content ?></a></span>
        <?php endif ?>
    <?php endforeach ?>

    <?php if ($last_page !== FALSE): ?>
        <span class='lado-pag-item l-p-next-items l-p-item-last'><a href="<?php echo HTML::chars($page->url($last_page)) ?>" rel="last"><?php echo __('Last') ?></a></span>
    <?php else: ?>
        <span class='lado-pag-item l-p-next-items lado-pag-item-without-link l-p-item-last'><?php echo __('Last') ?></span>
    <?php endif ?>

    <?php if ($next_page !== FALSE): ?>
        <span class='lado-pag-item l-p-next-items'><a href="<?php echo HTML::chars($page->url($next_page)) ?>" rel="next"><?php echo __('Next') ?></a></span>
    <?php else: ?>
        <span class='lado-pag-item l-p-next-items lado-pag-item-without-link'><?php echo __('Next') ?></span>
    <?php endif ?>
</div>
