<?php
	// Selected menu
	if (! isset($selected_menu))
		$selected_menu = '';
	//  Searching
	if (! isset($search_query))
		$search_query = '';
	if (! isset($search_category_name))
		$search_category_name = 'all-categories';
	// Categories
	$categories['all-categories'] = $this->lang->line('ui_categ_all-categories');
	foreach ($this->config->item('categories') as $id => $name)
	{
		$categories[$name] = $this->lang->line("ui_categ_$name");
	}
	// Username
	$username = $this->session->userdata('username');
?>

<ul
	id="nav-menu">
	<li class="menu-left"><a href="<?php echo site_url() ?>"


	<?php echo ($selected_menu == 'home' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_home') ?>
	</a></li>

	<li class="menu-left"><a
		href="<?php echo site_url('install-plugins') ?>"


		<?php echo ($selected_menu == 'install-plugins' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_install_plugins') ?>
	</a></li>

	<li class="menu-left"><a href="<?php echo site_url('about') ?>"


	<?php echo ($selected_menu == 'about' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_about') ?>
	</a></li>

	<li class="menu-left"><a href="<?php echo site_url('help') ?>"


	<?php echo ($selected_menu == 'help' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_help') ?>
	</a></li>

	<li class="menu-left"><a href="<?php echo site_url('contact') ?>"


	<?php echo ($selected_menu == 'contact' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_contact') ?>
	</a></li>

	<?php if ($username): ?>
	<li class="menu-right"><a href="<?php echo site_url('user/logout/' . urlencode_segments(uri_string())) ?>"
		<?php echo ($selected_menu == 'logout' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_logout') ?></a>
	</li>
	
	<li class="menu-right"><a href="<?php echo site_url('user/account/'. urlencode_segments(uri_string(), 'user/account')) ?>"
		<?php echo ($selected_menu == 'account' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_account') ?></a>
	</li>
	
	<li class="menu-right"><a href="<?php echo site_url('video/upload') ?>"
		<?php echo ($selected_menu == 'upload' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_upload') ?></a>
	</li>
	
	<li class="menu-right"><span class="menu-greeting">
		<?php echo $this->lang->line('ui_hello'). ', '. $username. '!&nbsp;&nbsp;&nbsp;' ?></span>
	</li> 
	
	<?php else: ?>
	<li class="menu-right"><a href="<?php echo site_url('user/register') ?>"
		<?php echo ($selected_menu == 'register' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_register') ?></a>
	</li>
		
	<li class="menu-right"><a href="<?php echo site_url('user/login/'. urlencode_segments(uri_string(), 'user/login')) ?>" 
		<?php echo ($selected_menu == 'login' ? 'class="selected"' : '') ?>><?php echo $this->lang->line('ui_nav_menu_log_in') ?></a>
	</li>
	<?php endif; ?>
</ul>

<div id="header">
	<!-- TODO: resize logo image-->
	<a href="<?php echo site_url() ?>" id="logo"><img
		src="<?php echo site_url('img/p2p-next--big.png') ?>" alt="P2P-Next"
		width="119" height="48" /> </a>
	
	<?php echo form_open('catalog/search', array('id'=>'quick-search')); ?>
		<label for="search-category"><?php echo $this->lang->line('ui_search_in') ?></label> <?php 
			echo form_dropdown('search-category', $categories, 
				$search_category_name, 'id="search-category"') ?>:
		<input type="text" id="search" name="search" value="<?php echo htmlentities($search_query) ?>" />
		<input type="submit" id="button-quick-search" value="<?php echo $this->lang->line('ui_search') ?>" />
		<a href="#" id="button-js-quick-search" style="display:none">
			<?php echo $this->lang->line('ui_search') ?>
		</a>
	</form>
</div>

<script type="text/javascript">
	$(function() {
		$('#button-quick-search')
			.hide();

		// Fake JS submit via CI URI segments
		var fakeSubmit = function() {
			var searchQuery = $('#search').val();

			if (searchQuery.length === 0)
			{
				alert('<?php echo $this->lang->line('error_search_query_empty') ?>');
				return;
			}
			
			searchQuery = searchQuery.replace(/\*/g, '_AST_');  // *
			searchQuery = searchQuery.replace(/\+/g, '_AND_');	// +
			//searchQuery = searchQuery.replace(/\-/g, '_');	// -
			searchQuery = searchQuery.replace(/\s/g, '+');	// <white spaces>
			searchQuery = searchQuery.replace(/>/g, '_GT_');	// >
			searchQuery = searchQuery.replace(/\</g, '_LT_');	// <
			searchQuery = searchQuery.replace(/\(/g, '_PO_');	// (
			searchQuery = searchQuery.replace(/\)/g, '_PC_');	// )
			searchQuery = searchQuery.replace(/~/g, '_LOW_');	// ~ 
			searchQuery = searchQuery.replace(/"/g, '_QUO_');	// " 
			searchQuery = encodeURI(searchQuery);

			searchCategoryName = $('#search-category').val();
			window.location = "<?php echo site_url('catalog/search') ?>/" 
				+ searchQuery + '/0'
				+ (searchCategoryName == 'all-categories' ? '' : '/'
					+ searchCategoryName);
		};
		
		$('#button-js-quick-search')
			.show()
			.button({
				icons: {
	                primary: "ui-icon-search"
	            },
	            text: false
			})
			.click(function(event) {
				fakeSubmit();
			});

		$('#search')
			.keypress(function(event) {
				if (event.which == 13)
				{
					fakeSubmit();

					event.preventDefault();
					return false;
				}
			});
	});

</script>
