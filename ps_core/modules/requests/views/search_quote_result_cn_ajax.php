
		<h3>搜索结果</h3>
			<?php if ($quoted_products): $isOdd=TRUE; ?>
			<table class="grid_6 no_margin table no_border " id="sorting-advanced" aria-describedby="sorting-advanced_info">

				<thead>
					<tr role="row">
					<th scope="col" width="15%" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Product name" style="width: 160px;"><?php echo order_link('/admin/requests/view_my_summary','productName','产品名称'); ?></th>
					<th scope="col" style="width: 80px;" class="align-center sorting" role="columnheader" tabindex="0" aria-controls="sorting-advanced" rowspan="1" colspan="1" aria-label="Created Date" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','dateQuoted','报价日期'); ?></th>
					
					<th scope="col" width="60" class="align-center sorting" role="columnheader" rowspan="1" colspan="1" aria-label="Assigned" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','material','材质'); ?></th>
					<th scope="col" width="60" class="align-center sorting" role="columnheader" rowspan="1" colspan="1" aria-label="Assigned" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','accepterName','报价人'); ?></th>
					<th scope="col" width="60" class="align-center sorting" role="columnheader" rowspan="1" colspan="1" aria-label="Assigned" style="width: 80px;"><?php echo order_link('/admin/requests/view_my_summary','factoryDetail','厂家信息'); ?></th>
				
					<th scope="col" width="150" class="align-center sorting" role="columnheader" rowspan="1" colspan="1"  style="width:150px;">操作</th>
					</tr>
					
				</thead>
			<tfoot>
			<tr><td colspan="6" rowspan="1">
					<?php echo $this->pagination->total_rows; ?> 报价单.
			</td></tr>
			</tfoot>
			<tbody role="alert" aria-live="polite" aria-relevant="all">					
			
			<?php foreach($quoted_products as $quoted_product):$style = ($isOdd)?'odd':'even'; $isOdd=!$isOdd; ?>			
			
				<tr class="<?php echo $style  ; ?>">
						<td class=" "><?php echo $quoted_product['productName']; ?></td>	
						<td class=""><?php echo $quoted_product['dateQuoted']; ?></td>
						<td class=""><?php echo $quoted_product['material']; ?></td>
						<td class=""><?php echo $quoted_product['accepterName']; ?></td>
						<td class=""><?php echo $quoted_product['factoryDetail']; ?></td>
		
						<td class="low-padding ">
							<span class="button-group compact">
								<a  class="button icon-copy view_quoted_product" id="<?php echo $quoted_product['quotedProductID'];?>">查看</a>
								 
								<a  class="button icon-copy copy_quoted_product" id="<?php echo $quoted_product['quotedProductID'];?>">复制</a>
				
								
							</span>
							
						</td>
				</tr>
	
			
			<?php endforeach; ?>
			</tbody>
			</table>
			<div class="">
				<?php    echo $this->pagination->create_links(); ?>
				&nbsp;
				
			</div>
			
		<?php else: ?>
			
			No  result found.
			
		<?php endif; ?>
		
