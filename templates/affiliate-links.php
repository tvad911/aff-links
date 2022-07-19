<?php
	if(!empty($items))
	{
		$collect = collect($items);
		$shopees = $collect->where('type', 'shopee')->all();
		$tikis = $collect->where('type', 'tiki')->all();
		$lazadas = $collect->where('type', 'lazada')->all();
?>
	<div class="affiliate-links">
		<?php
			if(!empty($shopees) && count($shopees) > 0) {
		?>
		<div class="group-aff">
			<h4 class="aff-title">
				<?php esc_html_e( 'Shopee', 'aff' ); ?>
			</h4>
			<ul class="list-affs">
				<?php
					foreach ($shopees as $item)
					{
				?>
				<li class="aff-item">
					<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?> - <?php echo number_format($item->price, 0, ',', '.') . ' đ'; ?></a>
				</li>
				<?php
					}
				?>
			</ul>
		</div>
		<?php
			}
		?>
		<?php
			if(!empty($tikis) && count($tikis) > 0) {
		?>
		<div class="group-aff">
			<h4 class="aff-title">
				<?php esc_html_e( 'Tiki', 'aff' ); ?>
			</h4>
			<ul class="list-affs">
				<?php
					foreach ($tikis as $item)
					{
				?>
				<li class="aff-item">
					<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?> - <?php echo number_format($item->price, 0, ',', '.') . ' đ'; ?></a>
				</li>
				<?php
					}
				?>
			</ul>
		</div>
		<?php
			}
		?>
		<?php
			if(!empty($lazadas) && count($lazadas) > 0) {
		?>
		<div class="group-aff">
			<h4 class="aff-title">
				<?php esc_html_e( 'Lazada', 'aff' ); ?>
			</h4>
			<ul class="list-affs">
				<?php
					foreach ($lazadas as $item)
					{
				?>
				<li class="aff-item">
					<a href="<?php echo $item->link; ?>"><?php echo $item->title; ?> - <?php echo number_format($item->price, 0, ',', '.') . ' đ'; ?></a>
				</li>
				<?php
					}
				?>
			</ul>
		</div>
		<?php
			}
		?>
	</div>

<?php
	}
?>