<?php

$route['shop/categories/(:num)'] = 'shop/categories/$1';
$route['shop/browse/(:num)'] = 'shop/browse/$1';
$route['shop/browse/(:num)/(:any)'] = 'shop/browse/$1/$2';
$route['shop/browse/(:num)/(:any)/(:any)'] = 'shop/browse/$1/$2/$3';
$route['shop/browse/(:num)/(:any)/(:any)/(:any)'] = 'shop/browse/$1/$2/$3/$4';
$route['shop/viewproduct/(:num)'] = 'shop/viewproduct/$1';
$route['shop/tag/(:any)'] = 'shop/tag/$1';
$route['shop/search'] = 'shop/search';
$route['shop/search/page'] = 'shop/search/page';
$route['shop/search/page/(:num)'] = 'shop/search/page/$1';
$route['shop/cart'] = 'shop/cart';

$route['shop/(:any)/(:any)/page/(:num)'] = 'shop/browse/$2/$1/page/$3';
$route['shop/(:any)/(:any)/page'] = 'shop/browse/$2/$1/page';
$route['shop/(:num)/(:any)'] = 'shop/viewproduct/$1';
$route['shop/(:any)/page/(:num)'] = 'shop/browse/$1/page/$2';
$route['shop/(:any)/page'] = 'shop/browse/$1/page';
$route['shop/(:any)/(:any)'] = 'shop/browse/$2/$1';
$route['shop/(:any)'] = 'shop/browse/$1';
$route['products/(:num)/(:any)'] = 'shop/viewproduct/$1';
