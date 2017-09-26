<h3><b>Edit entry:</b></h3>

<?php foreach ($data as $v) { ?>
	
	<form action="?act=apply-edit-news" method="post" class="well">
		<input type="hidden" name="id" value="<?php echo $v['id'];?>">
		<label>Title</label>
		<input name="title" type="text" value="<?php echo $v['title'];?>">
		<label>Catagory</label>
		<input name="catagory" type="text" value="<?php echo $v['catagory'];?>">	
		<div style="padding-top:15px;">
			<label>Text</label>
			<textarea name="text" cols="50" rows="10"><?php echo $v['text'];?></textarea>
		</div>
		<div style="padding-top: 10px;">
			<button type="submit" class="btn">Post</button>
		</div>
	</form>

<?php }?>