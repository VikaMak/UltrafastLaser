<style>
.entry {
	margin-bottom : 20px;
}

.comment {
	font-size : 0.8em;
}

</style>

<?php if (isset($_GET['id'])) {?>

	<?php foreach ($data[1] as $v) { ?>	

		<div class="entry">
			<h3><b><?php echo $v['title'];?></b></h3>
			<h5><b>Catagory: <?php echo $v['catagory'];?></b></h5>
			<p class="text-justify"><?php echo $v['text'];?></p>
			<p><?php echo $v['date'];?></p>
	  		
		</div>
		<h4><b><em>Comments:</em></b></h4>		
	
<?php if (!isset ($_SESSION['user'])) { ?>
	<p class="text-left">Зарегистрируйтесь, если хотите оставлять свои комментарии</p>
<?php } else {?>
	
		<div id="myform">
		<form action="" method="post" id="form1">
			<table>
				<tr>
					<td><b>Имя: </b></td>
					<td><input type="text" name="name" ></td>
				</tr>
				<tr>
					<td><b>Оставьте свой комментарий:</b></td>
					<td><textarea id="comment" rows="10" cols="55" name="text"></textarea></td>
				</tr>
			</table>
			<div align="center"><input type="submit" value="Отправить" id="submit"></div>
		</form>
	</div>
	
	<?php }?>	
	
		<?php foreach ($data[2] as $v) {?>
			<div class="entry" id="el">
				<p>
					<b><?php echo $v['name'];?></b>
				</p>
				<p class="text-justify"><?php echo $v['text'];?></p>
				<p class="comment"><?php echo $v['date'];?></p>
			</div>
		<?php }?>
		
		
	<?php }?>

<?php } else {?>

	<?php foreach ($data[0] as $v) { ?>	
			
			<div class="entry">		
				<h3>				
					<b><a href="?id=<?php echo $v['id'];?>"><?php echo $v['title'];?></a></b>
					<?php if (ADMIN) {?>
    					<a href="?act=edit-news&id=<?php echo $v['id']?>"><span class="glyphicon glyphicon-edit"></span></a>
    					<a href="?act=remove-news&id=<?php echo $v['id']?>"><span class="glyphicon glyphicon-remove"></span></a>
					<?php }?>
				</h3>
				<h5><b>Catagory: <?php echo $v['catagory'];?></b></h5>
				<p class="content"><?php echo $v['text'];?></p>
				<p><?php echo $v['date'];?></p>
		  		<a href="?id=<?php echo $v['id'];?>"><?php echo $v['comm'];?> Comment(s)</a>
			</div>	  	
		
	<?php }?>
	
	<div>
	
		<?php for($i = 1; $i <= $data['pages']; $i++) { ?>
			<?php if($i == $data['page']) {?>
				<b><?php echo $i;?></b>
			<?php } else {?><a href="?page=<?php echo $i;?>"><?php echo $i;?></a>
			<?php }	?>
	
		<?php }?>
	<?php }?>
	
	</div>
	
<?php if(ADMIN) {?>
<div style="margin-left : 40px;">
	<h3>Add news:</h3>
		
	<form action="?act=add-news" method="post">
		<label>Title</label>
		<input name="title" type="text">
		<label>Catagory</label>
		<input name="catagory" type="text">	
		<div style="padding-top:15px;">
			<label>Text</label>
			<textarea name="text" cols="50" rows="10"></textarea></div>
		<div style="padding-top: 10px;">
			<button type="submit" class="btn">Post</button>
		</div>
	</form>
</div>

<?php }?>

<script>
	
$(document).ready(function() {
	var x,y;
	$("#myform").on('submit', function(event) {
		
		x=$('input[name=name]').val();
		y=$('textarea[name=text]').val();	
		event.preventDefault();
		
		$.ajax({
			url     : "/02/UltrafastLaser/app/models/Ajax.php",
			type    :  "POST",
			cache   : false,
			timeout : 10000,
			data    : {
				"name"    : $("input[name='name']").val(),
				"comment" : $("textarea[name='text']").val(),	
				"id"	  : <?php echo $_GET['id'];?>,	
		},
		success : function(msg){
			if (x.length!=0 && y.lenght!=0) {
				console.log(msg);
				var d = new Date().toLocaleString();
				var result = JSON.parse(msg);
				
				$("<div>")
				.addClass("entry")
				.html("<p><b>"+result["name"]+"</b></p><p>"+result["comment"]+"</p><p class='comment'>"+d+"</p>")
				.prependTo("#el");	
			}
			
		}
		
		});
	});
});

</script>