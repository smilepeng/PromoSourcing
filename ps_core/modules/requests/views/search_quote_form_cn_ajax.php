<form method="post" action="<?php echo site_url($this->uri->uri_string()); ?>" enctype="multipart/form-data"  id="searchQuoteForm" name="searchQuoteForm" class="default">
<a class="window_close" href="#"><img src="<?php echo $this->config->item('staticPath'); ?>/images/btn_close.png" alt="Close" class="padded"></a><span class="window_title">报价查询</span>

<div style="clear:both;"></div>

<div id="window_scroll" class="container">

	<div class="grid_3 no_margin">	
	<p class="inline-label">
	<label class="label" for="productName">产品名称:</label>
	<?php echo @form_input('productName',set_value('productName',''), 'id="productName'.'" class="input float-left"'); ?>
	</p>
	<p class="inline-label">
	<label class="label" for="tags" >关键词:</label> <?php echo @form_input('tags',set_value('tags', ''), 'id="tags'.'" class="input float-left"'); ?>
	</p>
	<p class="inline-label">
	<label class="label" for="factoryDetail" >厂家信息:</label> <?php echo @form_input('factoryDetail',set_value('factoryDetail',''), 'id="factoryDetail'.'" class="input float-left"'); ?>
	</p>
	<p class="inline-label">
		<label class="label" for="material" >材质:</label> <?php echo @form_input('material',set_value('material', ''), 'id="material'.'" class="input float-left"'); ?>
		</p>
	</div>
	<div class="grid_3 no_margin">
		
		<p class="inline-label">
		<label class="label" for="colour" >颜色:</label> <?php echo @form_input('colour-Weeks',set_value('colour',''), 'id="colour" class="input float-left"'); ?>
		</p>
		<p class="inline-label">
		<label class="label" for="dateQuotedStart" >从报价日期:</label>  <input type="date" id="dateQuotedStart" name="dateQuotedStart" min="2014-09-01">
		</p>
			<p class="inline-label">
		<label class="label" for="dateQuotedEnd" >到报价日期:</label>  <input type="date" id="dateQuotedEnd" name="dateQuotedEnd" min="2014-09-01">
		</p>
		<div class="headingright">
			<a  class="button icon-search" id="search_quoted_product">查询</a>
		</div>
	</div>	
<h2 class="underline"></h2>
	<div id="found_products" class="grid_6 no_margin">
	
	</div>

</div>



	
</form>		


