<div class="_menu">
	<div class="mobMenu">
		<i data-control="_mobmenu" class="fa fa-comments"></i> 
	</div>
	<div class="_sideMenu">
		<div>
			<div class="form-items">
				<input type="text" class="form-control" id="phone_no" name="phone_no" value="" placeholder="Search">
			</div>
		</div>
		<div class="chatList">
			<div class="item ">
				<div class="clicker"></div>
				<div class="image">
					<img src="<?php echo $user_image ?>">
				</div>
				<div class="details">
					<div class="_details">
						<h1>Mahesh madushanka</h1>
						<h2>Hi mahesh..</h2>
					</div>
				</div>
			</div>
			<div class="item act">
				<div class="clicker"></div>
				<div class="image">
					<img src="<?php echo $user_image ?>">
				</div>
				<div class="details">
					<div class="_details">
						<h1>Mahesh madushanka</h1>
						<h2>Hi mahesh..</h2>
					</div>
				</div>
			</div>
			<div class="item act">
				<div class="clicker"></div>
				<div class="image">
					<img src="<?php echo $user_image ?>">
				</div>
				<div class="details">
					<div class="_details">
						<h1>Mahesh madushanka</h1>
						<h2>Hi mahesh..</h2>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
	.chatList{
		background-color: #f9f9f9;
		padding: 10px;
		height: calc(100vh - 200px);;
		overflow-y: auto;
	}
	
	.chatList .item{
		overflow: auto;
		position: relative;
	}
	
	.chatList .act{
		background-color: #ECECEC !important;
		border-top-left-radius: 50px;
		border-bottom-left-radius: 50px;
		margin-top: 5px;
	}
	
	.clicker {
		left: 0;
		top: 0;
		position: absolute;
		width: 100%;
		height: 100%;
		cursor: pointer;
	}
	
	.chatList .item .image{
		padding: 5px;
		height: 60px;
		width: 60px;
		float: left;
	}
	
	.chatList .item .image img{
		border-radius: 100%;
		height: 50px;
		width: 50px;
	}
	
	.chatList .item .details{
		padding-left: 60px;
		height: 60px;
		width: auto;
	}
	
	.chatList .item .details ._details{
		padding: 10px;
		height: 100%;
		border-bottom: 1px solid #ECECEC;
	}
	
	.chatList .item .details h1{
		margin: 0px;
		font-size: 12px;
	}
	
	.chatList .item .details h2{
		margin: 0px;
		font-size: 12px;
		font-weight: 100;
	}
	
</style>








