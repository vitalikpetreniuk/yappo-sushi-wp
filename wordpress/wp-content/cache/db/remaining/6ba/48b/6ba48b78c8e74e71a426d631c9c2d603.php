Q��d<?php exit; ?>a:6:{s:10:"last_error";s:0:"";s:10:"last_query";s:791:"SELECT wpml_translations.translation_id, wpml_translations.element_id, wpml_translations.language_code, wpml_translations.source_language_code, wpml_translations.trid, wpml_translations.element_type
				    FROM wp_icl_translations wpml_translations
				JOIN wp_posts p
					ON wpml_translations.element_id = p.ID
						AND wpml_translations.element_type = CONCAT('post_', p.post_type)
				    JOIN wp_icl_translations tridt
				      ON tridt.element_type = wpml_translations.element_type
				      AND tridt.trid = wpml_translations.trid
				    WHERE  tridt.trid = (SELECT trid FROM wp_icl_translations wpml_translations
				JOIN wp_posts p
					ON wpml_translations.element_id = p.ID
						AND wpml_translations.element_type = CONCAT('post_', p.post_type) WHERE element_id = 341 LIMIT 1)";s:11:"last_result";a:4:{i:0;O:8:"stdClass":6:{s:14:"translation_id";s:4:"1381";s:10:"element_id";s:4:"1911";s:13:"language_code";s:2:"ru";s:20:"source_language_code";s:2:"uk";s:4:"trid";s:3:"248";s:12:"element_type";s:12:"post_product";}i:1;O:8:"stdClass":6:{s:14:"translation_id";s:4:"1381";s:10:"element_id";s:4:"1911";s:13:"language_code";s:2:"ru";s:20:"source_language_code";s:2:"uk";s:4:"trid";s:3:"248";s:12:"element_type";s:12:"post_product";}i:2;O:8:"stdClass":6:{s:14:"translation_id";s:3:"292";s:10:"element_id";s:3:"341";s:13:"language_code";s:2:"uk";s:20:"source_language_code";N;s:4:"trid";s:3:"248";s:12:"element_type";s:12:"post_product";}i:3;O:8:"stdClass":6:{s:14:"translation_id";s:3:"292";s:10:"element_id";s:3:"341";s:13:"language_code";s:2:"uk";s:20:"source_language_code";N;s:4:"trid";s:3:"248";s:12:"element_type";s:12:"post_product";}}s:8:"col_info";a:6:{i:0;O:8:"stdClass":13:{s:4:"name";s:14:"translation_id";s:7:"orgname";s:14:"translation_id";s:5:"table";s:17:"wpml_translations";s:8:"orgtable";s:19:"wp_icl_translations";s:3:"def";s:0:"";s:2:"db";s:5:"yappo";s:7:"catalog";s:3:"def";s:10:"max_length";i:4;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:49667;s:4:"type";i:8;s:8:"decimals";i:0;}i:1;O:8:"stdClass":13:{s:4:"name";s:10:"element_id";s:7:"orgname";s:10:"element_id";s:5:"table";s:17:"wpml_translations";s:8:"orgtable";s:19:"wp_icl_translations";s:3:"def";s:0:"";s:2:"db";s:5:"yappo";s:7:"catalog";s:3:"def";s:10:"max_length";i:4;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:49160;s:4:"type";i:8;s:8:"decimals";i:0;}i:2;O:8:"stdClass":13:{s:4:"name";s:13:"language_code";s:7:"orgname";s:13:"language_code";s:5:"table";s:17:"wpml_translations";s:8:"orgtable";s:19:"wp_icl_translations";s:3:"def";s:0:"";s:2:"db";s:5:"yappo";s:7:"catalog";s:3:"def";s:10:"max_length";i:2;s:6:"length";i:28;s:9:"charsetnr";i:246;s:5:"flags";i:20481;s:4:"type";i:253;s:8:"decimals";i:0;}i:3;O:8:"stdClass":13:{s:4:"name";s:20:"source_language_code";s:7:"orgname";s:20:"source_language_code";s:5:"table";s:17:"wpml_translations";s:8:"orgtable";s:19:"wp_icl_translations";s:3:"def";s:0:"";s:2:"db";s:5:"yappo";s:7:"catalog";s:3:"def";s:10:"max_length";i:2;s:6:"length";i:28;s:9:"charsetnr";i:246;s:5:"flags";i:0;s:4:"type";i:253;s:8:"decimals";i:0;}i:4;O:8:"stdClass":13:{s:4:"name";s:4:"trid";s:7:"orgname";s:4:"trid";s:5:"table";s:17:"wpml_translations";s:8:"orgtable";s:19:"wp_icl_translations";s:3:"def";s:0:"";s:2:"db";s:5:"yappo";s:7:"catalog";s:3:"def";s:10:"max_length";i:3;s:6:"length";i:20;s:9:"charsetnr";i:63;s:5:"flags";i:53257;s:4:"type";i:8;s:8:"decimals";i:0;}i:5;O:8:"stdClass":13:{s:4:"name";s:12:"element_type";s:7:"orgname";s:12:"element_type";s:5:"table";s:17:"wpml_translations";s:8:"orgtable";s:19:"wp_icl_translations";s:3:"def";s:0:"";s:2:"db";s:5:"yappo";s:7:"catalog";s:3:"def";s:10:"max_length";i:12;s:6:"length";i:240;s:9:"charsetnr";i:246;s:5:"flags";i:16393;s:4:"type";i:253;s:8:"decimals";i:0;}}s:8:"num_rows";i:4;s:10:"return_val";i:4;}