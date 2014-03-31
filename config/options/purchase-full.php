<?php 

function arrayToList(array $items, $type) {
	$output = '<'.$type.'>';
	foreach($items as $item) {
		$output .= '<li>'.$item.'</li>';
	}
	$output .= '</'.$type.'>';
	return $output;
}

$rows = array();

$rows[] = array(
	'label' => 'Price',
	'values' => array(
		array('text', 'Free'),
		array('text', '$12'),
		array('text', '$60')
	)
);

$rows[] = array(
	'label' => 'Shortcodes',
	'values' => array(
		array('text', arrayToList(array('Static Tweets'), 'ul')),
		array('text', arrayToList(array('Static Tweets','Scrolling Tweets','Sliding Tweets'), 'ul')),
		array('text', arrayToList(array('Static Tweets','Scrolling Tweets','Sliding Tweets'), 'ul'))
	)
);

$rows[] = array(
	'label' => 'Twitter Resources',
	'values' => array(
		array('text', arrayToList(array('User Timeline'), 'ul')),
		array('text', arrayToList(array('User Timeline','Home Timeline','Mentions Timeline', 'Retweets of Me', 'Search', 'List'), 'ul')),
		array('text', arrayToList(array('User Timeline','Home Timeline','Mentions Timeline', 'Retweets of Me', 'Search', 'List'), 'ul'))
	)
);

$rows[] = array(
	'label' => 'Custom Tweet Skins',
	'values' => array(
		array('text', arrayToList(array('Default','Simplistic'), 'ul')),
		array('text', arrayToList(array('Default','Simplistic','Futuristic', 'Talk Bubble', 'LED Screen'), 'ul')),
		array('text', arrayToList(array('Default','Simplistic','Futuristic', 'Talk Bubble', 'LED Screen'), 'ul'))
	)
);

$rows[] = array(
	'label' => 'Shortcode Editor',
	'values' => array(
		array('symbol', 'check'),
		array('symbol', 'check'),
		array('symbol', 'check')
	)
);

$rows[] = array(
	'label' => 'RTL Language Support',
	'values' => array(
		array('symbol', 'check'),
		array('symbol', 'check'),
		array('symbol', 'check')
	)
);

$rows[] = array(
	'label' => 'Caching System',
	'values' => array(
		array('symbol', 'check'),
		array('symbol', 'check'),
		array('symbol', 'check')
	)
);

$rows[] = array(
	'label' => 'Widget',
	'values' => array(
		array('symbol', 'check'),
		array('symbol', 'check'),
		array('symbol', 'check')
	)
);

$rows[] = array(
	'label' => 'Responsive',
	'values' => array(
		array('symbol', 'check'),
		array('symbol', 'check'),
		array('symbol', 'check')
	)
);

$rows[] = array(
	'label' => 'Extended License',
	'values' => array(
		array('symbol', ''),
		array('symbol', ''),
		array('symbol', 'check')
	)
);

$table = array (
	'header' => array(
		'Demo', 'Full', 'Extended'
	),
	'rows' => $rows,
	'footer' => array(
		'Owned', 
		'<a href="http://codecanyon.net/item/twitter-feed-social-plugin-for-wordpress/6665168?ref=Askupa" target="_blank">Get Full Version</a>', 
		'<a href="http://codecanyon.net/item/twitter-feed-social-plugin-for-wordpress/6665168?ref=Askupa" target="_blank">Get Extended Version</a>'
	)
);
?>
<style>
	.table-wrapper {
		margin: 20px 100px;
	}
	table.pricing-table {
		width: 100%;
		table-layout: fixed;
		border-collapse: collapse;
	}
	table.pricing-table th,
	table.pricing-table td {
		text-align: center;
		border-bottom: 1px solid #CCC;
		padding: 5px 10px;
		border-right: 1px solid #CCC;
	}
	table.pricing-table tbody th {
		text-align: right;
	}
	table.pricing-table tr.odd {
		background-color: #EEE;
	}
	table.pricing-table ul {
		list-style-type: square;
		text-align: left;
		padding-left: 27px;
	}
	table.pricing-table tfoot td.first {
		border-bottom: none;
	}
	table.pricing-table tfoot {
		font-size: 16px;
	}
	table.pricing-table tfoot td {
		padding:0;
	}
	table.pricing-table tfoot a {
		display: block;
		text-decoration: none;
		padding: 10px 15px;
	}
	table.pricing-table tfoot a:hover {
		background-color: #3FA6D5;
		color:white;
		text-shadow: 0 -1px 0 rgba(0,0,0,0.5);
		box-shadow: inset 0 0 15px rgba(0,0,0,0.2);
	}
</style>
<div class="table-wrapper">
	<table class="pricing-table">
		<thead>
			<tr class="even">
				<th></th><?php foreach($table['header'] as $e) echo '<th>'.$e.'</th>'; ?>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1;
			foreach($table['rows'] as $row) {
			echo '<tr class="'.($i % 2 == 0 ? 'even' : 'odd').'"><th>'.$row['label'].'</th>';
			foreach($row['values'] as $row)
				echo '<td>'.($row[0] == 'symbol' ? '<i class="fa fa-'.$row[1].'"></i>' : $row[1]).'</td>';
			$i++;
			} ?>
		</tbody>
		<tfoot>
			<tr>
				<td class="first"></td><?php foreach($table['footer'] as $e) echo '<td>'.$e.'</td>'; ?>
			</tr>
		</tfoot>
	</table>
</div>