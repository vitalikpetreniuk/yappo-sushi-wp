import { getCacheSlug } from '../addons/cache';

/**
 * Build the data object to send with the Ajax request.
 *
 * @param {object} alm       The ALM object.
 * @param {string} queryType The query type.
 * @return {object}          The data object.
 * @since 3.6
 */
export function getAjaxParams(alm, queryType) {
	const { addons, extensions } = alm;

	// Defaults
	const data = {
		action: 'alm_get_posts',
		query_type: queryType,
		id: alm.id,
		post_id: parseInt(alm.post_id),
		slug: alm.slug,
		canonical_url: encodeURIComponent(alm.canonical_url),
		posts_per_page: parseInt(alm.posts_per_page),
		page: parseInt(alm.page),
		offset: parseInt(alm.offset),
		post_type: alm.post_type,
		repeater: alm.repeater,
		seo_start_page: alm.start_page,
	};

	// Addons & Extensions

	if (extensions.acf) {
		data.acf = getTypeParams(alm, 'acf');
		if (extensions.acf_field_type !== 'relationship') {
			data.action = 'alm_acf';
		}
	}
	if (addons.comments === 'true') {
		data.comments = getTypeParams(alm, 'comments');
		data.posts_per_page = addons.comments_per_page;
		data.action = 'alm_comments';
	}
	if (addons.cta) {
		data.cta = getTypeParams(alm, 'cta');
	}
	if (addons.filters) {
		data.filters = addons.filters;
		data.filters_startpage = addons.filters_startpage;
		data.filters_target = addons.filters_target;
		data.facets = alm.facets;
	}
	if (addons.nextpage) {
		data.nextpage = getTypeParams(alm, 'nextpage');
		data.action = 'alm_nextpage';
	}
	if (addons.paging) {
		data.paging = addons.paging;
	}
	if (addons.preloaded === 'true') {
		data.preloaded = addons.preloaded;
		data.preloaded_amount = parseInt(addons.preloaded_amount);
	}
	if (addons.single_post) {
		data.single_post = getTypeParams(alm, 'single_post');
	}
	if (extensions.term_query) {
		data.term_query = getTypeParams(alm, 'term_query');
		data.action = 'alm_get_terms';
	}
	if (alm.theme_repeater) {
		data.theme_repeater = alm.theme_repeater;
	}
	if (alm.addons.users) {
		data.users = getTypeParams(alm, 'users');
		data.action = 'alm_users';
	}

	// Query Data Params

	if (alm.listing.dataset.lang) {
		data.lang = alm.listing.dataset.lang;
	}
	if (alm.listing.dataset.stickyPosts) {
		data.sticky_posts = alm.listing.dataset.stickyPosts;
	}
	if (alm.listing.dataset.postFormat) {
		data.post_format = alm.listing.dataset.postFormat;
	}
	if (alm.listing.dataset.category) {
		data.category = alm.listing.dataset.category;
	}
	if (alm.listing.dataset.categoryAnd) {
		data.category__and = alm.listing.dataset.categoryAnd;
	}
	if (alm.listing.dataset.categoryNotIn) {
		data.category__not_in = alm.listing.dataset.categoryNotIn;
	}
	if (alm.listing.dataset.tag) {
		data.tag = alm.listing.dataset.tag;
	}
	if (alm.listing.dataset.tagAnd) {
		data.tag__and = alm.listing.dataset.tagAnd;
	}
	if (alm.listing.dataset.tagNotIn) {
		data.tag__not_in = alm.listing.dataset.tagNotIn;
	}
	if (alm.listing.dataset.taxonomy) {
		data.taxonomy = alm.listing.dataset.taxonomy;
	}
	if (alm.listing.dataset.taxonomyTerms) {
		data.taxonomy_terms = alm.listing.dataset.taxonomyTerms;
	}
	if (alm.listing.dataset.taxonomyOperator) {
		data.taxonomy_operator = alm.listing.dataset.taxonomyOperator;
	}
	if (alm.listing.dataset.taxonomyIncludeChildren) {
		data.taxonomy_include_children = alm.listing.dataset.taxonomyIncludeChildren;
	}
	if (alm.listing.dataset.taxonomyRelation) {
		data.taxonomy_relation = alm.listing.dataset.taxonomyRelation;
	}
	if (alm.listing.dataset.metaKey) {
		data.meta_key = alm.listing.dataset.metaKey;
	}
	if (alm.listing.dataset.metaValue) {
		data.meta_value = alm.listing.dataset.metaValue;
	}
	if (alm.listing.dataset.metaCompare) {
		data.meta_compare = alm.listing.dataset.metaCompare;
	}
	if (alm.listing.dataset.metaRelation) {
		data.meta_relation = alm.listing.dataset.metaRelation;
	}
	if (alm.listing.dataset.metaType) {
		data.meta_type = alm.listing.dataset.metaType;
	}
	if (alm.listing.dataset.author) {
		data.author = alm.listing.dataset.author;
	}
	if (alm.listing.dataset.year) {
		data.year = alm.listing.dataset.year;
	}
	if (alm.listing.dataset.month) {
		data.month = alm.listing.dataset.month;
	}
	if (alm.listing.dataset.day) {
		data.day = alm.listing.dataset.day;
	}
	if (alm.listing.dataset.order) {
		data.order = alm.listing.dataset.order;
	}
	if (alm.listing.dataset.orderby) {
		data.orderby = alm.listing.dataset.orderby;
	}
	if (alm.listing.dataset.postStatus) {
		data.post_status = alm.listing.dataset.postStatus;
	}
	if (alm.listing.dataset.postIn) {
		data.post__in = alm.listing.dataset.postIn;
	}
	if (alm.listing.dataset.postNotIn) {
		data.post__not_in = alm.listing.dataset.postNotIn;
	}
	if (alm.listing.dataset.exclude) {
		data.exclude = alm.listing.dataset.exclude;
	}
	if (alm.listing.dataset.search) {
		data.search = alm.listing.dataset.search;
	}
	if (alm.listing.dataset.s) {
		data.search = alm.listing.dataset.s;
	}
	if (alm.listing.dataset.customArgs) {
		data.custom_args = escape(alm.listing.dataset.customArgs);
	}
	if (alm.listing.dataset.vars) {
		data.vars = escape(alm.listing.dataset.vars);
	}

	// Cache Params

	if (addons.cache) {
		data.cache_id = addons.cache_id;
		data.cache_logged_in = addons.cache_logged_in;
		data.cache_slug = getCacheSlug(alm, data);
	}

	return data;
}

/**
 * Build the query params for content types.
 *
 * @param {object} alm  The ALM object.
 * @param {string} type The query type.
 * @return {object}     The query params.
 */
export function getTypeParams(alm, type) {
	const { addons, extensions } = alm;
	switch (type) {
		case 'acf':
			return {
				acf: 'true',
				post_id: extensions.acf_post_id,
				field_type: extensions.acf_field_type,
				field_name: extensions.acf_field_name,
				parent_field_name: extensions.acf_parent_field_name,
				row_index: extensions.acf_row_index,
			};

		case 'comments':
			return {
				comments: 'true',
				post_id: addons.comments_post_id,
				per_page: addons.comments_per_page,
				type: addons.comments_type,
				style: addons.comments_style,
				template: addons.comments_template,
				callback: addons.comments_callback,
			};

		case 'cta':
			return {
				cta: 'true',
				cta_position: addons.cta_position,
				cta_repeater: addons.cta_repeater,
				cta_theme_repeater: addons.cta_theme_repeater,
			};

		case 'nextpage':
			return {
				nextpage: 'true',
				urls: addons.nextpage_urls,
				scroll: addons.nextpage_scroll,
				pageviews: addons.nextpage_pageviews,
				post_id: addons.nextpage_post_id,
				startpage: addons.nextpage_startpage,
				nested: alm.nested,
			};

		case 'single_posts':
			return {
				single_post: 'true',
				id: addons.single_post_id,
				slug: addons.single_post_slug,
			};

		case 'term_query':
			return {
				term_query: 'true',
				taxonomy: extensions.term_query_taxonomy,
				hide_empty: extensions.term_query_hide_empty,
				number: extensions.term_query_number,
			};

		case 'users':
			return {
				users: 'true',
				role: alm.listing.dataset.usersRole,
				include: alm.listing.dataset.usersInclude,
				exclude: alm.listing.dataset.usersExclude,
				per_page: alm.posts_per_page,
				order: alm.listing.dataset.usersOrder,
				orderby: alm.listing.dataset.usersOrderby,
			};
	}
}

/**
 * Build the REST API data object to send with REST API request.
 *
 * @param {object} alm The ALM object.
 * @return {object}    The data object.
 * @since 3.6
 */
export function getRestAPIParams(alm) {
	const data = {
		id: alm.id,
		post_id: parseInt(alm.post_id),
		posts_per_page: alm.posts_per_page,
		page: alm.page,
		offset: alm.offset,
		slug: alm.slug,
		canonical_url: encodeURIComponent(alm.canonical_url),
		post_type: alm.post_type,
		post_format: alm.listing.dataset.postFormat,
		category: alm.listing.dataset.category,
		category__not_in: alm.listing.dataset.categoryNotIn,
		tag: alm.listing.dataset.tag,
		tag__not_in: alm.listing.dataset.tagNotIn,
		taxonomy: alm.listing.dataset.taxonomy,
		taxonomy_terms: alm.listing.dataset.taxonomyTerms,
		taxonomy_operator: alm.listing.dataset.taxonomyOperator,
		taxonomy_relation: alm.listing.dataset.taxonomyRelation,
		meta_key: alm.listing.dataset.metaKey,
		meta_value: alm.listing.dataset.metaValue,
		meta_compare: alm.listing.dataset.metaCompare,
		meta_relation: alm.listing.dataset.metaRelation,
		meta_type: alm.listing.dataset.metaType,
		author: alm.listing.dataset.author,
		year: alm.listing.dataset.year,
		month: alm.listing.dataset.month,
		day: alm.listing.dataset.day,
		post_status: alm.listing.dataset.postStatus,
		order: alm.listing.dataset.order,
		orderby: alm.listing.dataset.orderby,
		post__in: alm.listing.dataset.postIn,
		post__not_in: alm.listing.dataset.postNotIn,
		search: alm.listing.dataset.search,
		s: alm.listing.dataset.s,
		custom_args: alm.listing.dataset.customArgs,
		vars: alm.listing.dataset.vars,
		lang: alm.lang,
		preloaded: alm.addons.preloaded,
		preloaded_amount: alm.addons.preloaded_amount,
		seo_start_page: alm.start_page,
	};
	return data;
}
