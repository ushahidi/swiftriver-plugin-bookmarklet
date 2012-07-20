	<div class="bookmarklet">
		<article class="modal" id="bookmarklet">
			<hgroup class="page-title cf">
				<div class="page-h1 col_9">
					<h1>Add to bucket</h1>
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

			<div class="modal-body select-list" style="display: block;">
				<p style="display: block;" class="category own-title">Your buckets</p>
				<form class="own">
				<label>
			<input type="checkbox">
			bookmarks
		</label><label>
			<input type="checkbox">
			test
		</label><label>
			<input type="checkbox">
			せおる
		</label></form>

				<p style="display: block;" class="category collaborating-title">Buckets you collaborate on</p>
				<form class="collaborating">
				<label>
			<input type="checkbox">
			69mb / bookmarks
		</label><label>
			<input type="checkbox">
			angie / Ushahidi
		</label></form>

				<p style="display:none" class="category following-title">Buckets you follow</p>
				<form class="following">
				</form>
			</div>
			<div class="modal-body create-new">
				<form>
					<h2>Create a new bucket</h2>
					<div class="field">
						<input type="text" name="new_bucket" class="name" placeholder="Name your new bucket">
						<p class="button-blue"><a href="#">Save and add drop</a></p>
					</div>
					<div class="system_error"></div>
				</form>
			</div>
			<section class="drop-summary cf">
				<a class="avatar-wrap"><?php echo Html::image("themes/default/media/img/avatar_default.gif") ?></a>
				<div class="drop-content">
					<p><?php echo $drop['droplet_title']; ?></p>
					<p class="drop-source-channel rss"><a href="<?php echo $url; ?>" target="_blank"><span class="icon"></span>via web</a></p>
				</div>
			</section>
		</article>
	</div>