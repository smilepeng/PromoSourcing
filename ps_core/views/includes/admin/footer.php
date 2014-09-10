
			<div id="halogycms_browser" class="loading"></div>

		</div>

	</div>


	<div id="footer" class="content">

		<div class="container">

		
			<p class="copyright">Powered by promosourcing</p>
			
			<br />
			
			<a href=""><img src="<?php echo $this->config->item('staticPath'); ?>/images/ps_logo.png" alt="Promo Sourcing" /></a>


		</div>

	</div>

</div>
	
	
	

	
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/setup.js"></script>
	
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/developr.input.js"></script>


	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.knob.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.ui.widget.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.iframe-transport.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/jquery.fileupload.js"></script>		
	
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/default.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo $this->config->item('staticPath'); ?>/js/admin.js"></script>	

	
	<script language="JavaScript">			
		$(function(){
			$('ul#menubar li').hover(
				function() { $('ul', this).css('display', 'block').parent().addClass('hover'); },
				function() { $('ul', this).css('display', 'none').parent().removeClass('hover'); }
			);			
		});		
	</script>		
</body>
</html>