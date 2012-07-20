	<script type="text/javascript">
		$(function() {
		
		var drop = new Drops.Drop(<?php echo $drop?>);
		
		if (drop.get("id")) {
			drop.url = "<?php echo $base_url ?>/drops/" + drop.get("id");
			var view = new Drops.AddToBucketView({collection: Assets.bucketList, model: drop});
			$("#bookmarklet").append(view.render().$el);
		} else {
			$("#bookmarklet").append($("#error-message").show());
		}
	});
	</script>
	
	<script type="text/template" id="bucket-template">
		<input type="checkbox" <% if (containsDrop) { %> checked <% } %>/>
		<%= display_name %>
	</script>
	
	<script type="text/template" id="add-to-bucket-template">
	<article class="modal">
		<hgroup class="page-title cf">
			<div class="page-h1 col_9">
				<h1><?php echo __("Add to bucket"); ?></h1>
			</div>
			<div class="page-actions col_3">
				<h2 class="close">
					<a href="#">
						<span class="icon"></span>
						<?php echo __("Close"); ?>
					</a>
				</h2>
			</div>
		</hgroup>

		<div class="modal-body select-list">
			<p class="category own-title" style="display:none">Your buckets</p>
			<form class="own">
			</form>

			<p class="category collaborating-title" style="display:none">Buckets you collaborate on</p>
			<form class="collaborating">
			</form>

			<p class="category following-title" style="display:none">Buckets you follow</p>
			<form class="following">
			</form>
		</div>
		<div class="modal-body create-new">
			<form>
				<h2><?php echo __("Create a new bucket"); ?></h2>
				<div class="field">
					<input type="text" placeholder="Name your new bucket" class="name" name="new_bucket" />
					<p class="button-blue"><a href="#">Save and add drop</a></p>
				</div>
				<div class="system_error"></div>
			</form>
		</div>
		<section class="drop-summary cf">
			<a class="avatar-wrap"><img src="<%= identity_avatar %>" /></a>
			<div class="drop-content">
				<p><strong><%= identity_name %>:</strong> <%= droplet_title %></p>
				<p class="drop-source-channel rss"><a href="#"><span class="icon"></span>via <%= channel %></a></p>
			</div>
		</section>
	</article>
	</script>
	
	<div id="error-message" class="nodisplay">
		<article class="modal">
			<hgroup class="page-title cf">
				<div class="page-h1 col_9">
					<h1>Oops</h1>
				</div>
				<div class="page-actions col_3">
					<h2 class="close">
						<a href="#">
							<span class="icon"></span>
							Close
						</a>
					</h2>
				</div>
			</hgroup>
        	
			<div class="modal-body">
				<div class="alert-message red">
					<p><strong>No content found.</strong></p>
				</div>
			</div>
		</article>
	</div>
	
	<div style="background: #fff;" id="bookmarklet">
	</div>