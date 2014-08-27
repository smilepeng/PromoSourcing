<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 */

// ------------------------------------------------------------------------

/**
 * site config variables
 *
**/

// set paths
$config['sitePath']		=	'/PromoSourcing';
$config['includesPath']		=	'/includes/admin';			// path to admin header and footer files
$config['uploadsPath']		=	'/assets/uploads/files';			// where to upload files (must be 777)
$config['staticFolder']		=	'/assets';					// where are the images hosted
$config['staticPath']		=	$config['sitePath'].$config['staticFolder']	;					// where are the images hosted
$config['noPictureFile']		=	'nopicture.jpg'	;					// the name of image file, it should be hosted in folder of staticPath/image
$config['logoPath']			=	'';							// the administration logo
$config['stagingSites']		=	FALSE;						// whether to create upload folders for each site automatically (for MSM)
		
/* END OF FILE */