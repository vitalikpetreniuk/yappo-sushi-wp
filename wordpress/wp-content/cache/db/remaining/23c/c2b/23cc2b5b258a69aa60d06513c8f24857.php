Dà¸d<?php exit; ?>a:6:{s:10:"last_error";s:0:"";s:10:"last_query";s:1090:"
			SELECT DISTINCT t.term_id
			FROM wp_terms AS t  LEFT JOIN wp_termmeta ON ( t.term_id = wp_termmeta.term_id AND wp_termmeta.meta_key='order') INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id LEFT JOIN wp_icl_translations icl_t
                                    ON icl_t.element_id = tt.term_taxonomy_id
                                        AND icl_t.element_type IN ('tax_product_cat')
			WHERE tt.taxonomy IN ('product_cat') AND t.term_id IN ( 36,37,38,39,40,41,42,43,44,45,102 ) AND ( 
  ( wp_termmeta.meta_key = 'order' OR wp_termmeta.meta_key IS NULL )
) AND ( ( icl_t.element_type IN ('tax_product_cat')  AND ( icl_t.language_code = 'uk' OR (
					icl_t.language_code = 'uk'
					AND tt.taxonomy IN ( 'product_cat' )
					AND ( ( 
			( SELECT COUNT(element_id)
			  FROM wp_icl_translations
			  WHERE trid = icl_t.trid
			  AND language_code = 'uk'
			) = 0
			 ) OR ( 0 ) ) 
				) )  )
                                    OR icl_t.element_type NOT IN ('tax_product_cat') OR icl_t.element_type IS NULL ) 
			ORDER BY wp_termmeta.meta_value+0 ASC, t.name ASC
			
		";s:11:"last_result";a:11:{i:0;O:8:"stdClass":1:{s:7:"term_id";s:2:"39";}i:1;O:8:"stdClass":1:{s:7:"term_id";s:2:"42";}i:2;O:8:"stdClass":1:{s:7:"term_id";s:2:"40";}i:3;O:8:"stdClass":1:{s:7:"term_id";s:2:"44";}i:4;O:8:"stdClass":1:{s:7:"term_id";s:2:"37";}i:5;O:8:"stdClass":1:{s:7:"term_id";s:2:"38";}i:6;O:8:"stdClass":1:{s:7:"term_id";s:2:"43";}i:7;O:8:"stdClass":1:{s:7:"term_id";s:2:"41";}i:8;O:8:"stdClass":1:{s:7:"term_id";s:2:"45";}i:9;O:8:"stdClass":1:{s:7:"term_id";s:2:"36";}i:10;O:8:"stdClass":1:{s:7:"term_id";s:3:"102";}}s:8:"col_info";a:1:{i:0;O:8:"stdClass":13:{s:4:"name";s:7:"term_id";s:7:"orgname";s:7:"term_id";s:5:"table";s:1:"t";s:8:"orgtable";s:8:"wp_terms";s:3:"def";s:0:"";s:2:"db";s:5:"yappo";s:7:"catalog";s:3:"def";s:10:"max_length";i:3;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:49185;s:4:"type";i:8;s:8:"decimals";i:0;}}s:8:"num_rows";i:11;s:10:"return_val";i:11;}