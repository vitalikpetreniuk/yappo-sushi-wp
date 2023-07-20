Eà¸d<?php exit; ?>a:6:{s:10:"last_error";s:0:"";s:10:"last_query";s:831:"
			SELECT  t.term_id
			FROM wp_terms AS t  INNER JOIN wp_term_taxonomy AS tt ON t.term_id = tt.term_id LEFT JOIN wp_icl_translations icl_t
                                    ON icl_t.element_id = tt.term_taxonomy_id
                                        AND icl_t.element_type IN ('tax_product_cat')
			WHERE tt.taxonomy IN ('product_cat') AND t.term_id IN ( 38 ) AND ( ( icl_t.element_type IN ('tax_product_cat')  AND ( icl_t.language_code = 'uk' OR (
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
			
			
		";s:11:"last_result";a:1:{i:0;O:8:"stdClass":1:{s:7:"term_id";s:2:"38";}}s:8:"col_info";a:1:{i:0;O:8:"stdClass":13:{s:4:"name";s:7:"term_id";s:7:"orgname";s:7:"term_id";s:5:"table";s:1:"t";s:8:"orgtable";s:8:"wp_terms";s:3:"def";s:0:"";s:2:"db";s:5:"yappo";s:7:"catalog";s:3:"def";s:10:"max_length";i:2;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:49699;s:4:"type";i:8;s:8:"decimals";i:0;}}s:8:"num_rows";i:1;s:10:"return_val";i:1;}