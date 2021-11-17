<?php
namespace DanceStudioManager;
$result = App::GetClient()->GetController('members')->GetGiftCards();
$gift_card_data = $result->data;
?>
<div id="tab-members-purchases" class="tab-pane">
	<h2 class="page-header">Gift Cards</h2>
	<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<? if (!empty($gift_card_data->table_header)) : ?>
				<? foreach ($gift_card_data->table_header as $h) : ?>
				<th style="text-align:right;"><?php echo $h; ?></th>
				<? endforeach; ?>
				<? endif; ?>
			</tr>
		</thead>
		<tbody>
		<? if (!empty($gift_card_data->list)) : ?>
			<? foreach ($gift_card_data->list as $row) : ?>
			<tr>
				<? foreach ($row as $col): ?>
				<td  style="white-space: nowrap;text-align:right;"><?php echo $col; ?></td>
				<? endforeach; ?>
			</tr>
			<? endforeach; ?>
		<? else: ?>
			<tr>
				<td colspan="6">No records found</td>
			</tr>
		<? endif; ?>
		</tbody>
	</table>
	</div>
</div>