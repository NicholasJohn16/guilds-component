<ul class="thumbnails">
	<?php foreach($this->characters as $character):?>
		<li class="span3">
			<div class="thumbnail">
				<a><img src="images/characters/<?php echo $character->user_id;?>/<?php echo $character->id; ?>.png");"/></a>
				<h5><?php echo $character->name;?></h5>
				<p>
				<?php foreach($this->types as $type):?>
				<?php $type_name = $type->name.'_name';?>
				<?php echo $character->$type_name;?><br/>
				<?php endforeach;?>
				</p>
				<p>
					<button title="Star" class="btn btn-warning"><i class="icon-star icon-white"></i></button>
					<button title="Unpublish" class="btn btn-primary"><i class="icon-eye-close icon-white"></i></button>
					<button title="Delete" class="btn btn-danger"><i class="icon-remove icon-white"></i></button>
				</p>
			</div>
				
		</li>
	<?php endforeach; ?>
</ul>