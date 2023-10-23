/**
 * List of all javascript packages that will be loaded by webpack.
 */
const packages = {
  'acfwf-admin-app': './packages/acfwf-admin-app/index.tsx',
  'acfwf-edit-advanced-coupon': './packages/acfwf-edit-advanced-coupon/index.ts',
  'acfwf-blocks': './packages/acfwf-blocks/index.tsx',
  'acfwf-wc-admin': './packages/acfwf-wc-admin/index.tsx',
  'acfwf-admin': './packages/acfwf-admin/index.ts',
  'acfwf-edit-order': './packages/acfwf-edit-order/index.ts',
  'acfwf-notices': './packages/acfwf-notices/index.ts',
  'acfwf-checkout': './packages/acfwf-checkout/index.ts',
  'acfwf-store-credits-frontend': './packages/acfwf-store-credits-frontend/index.tsx',
};

module.exports = {
  packages: packages,

  // list packages that needs to be loaded via 'ts-loader'.
  tsPackages: ['acfwf-edit-advanced-coupon', 'acfwf-admin', 'acfwf-edit-order', 'acfwf-notices', 'acfwf-checkout'],

  // list packages that needs to be loaded via babel-react loader.
  reactPackages: ['acfwf-admin-app', 'acfwf-store-credits-frontend'],

  // list packages that needs to be loaded via 'babel-loader' with WP dependency extraction setup.
  wpBabelPackages: ['acfwf-blocks', 'acfwf-wc-admin'],
};
