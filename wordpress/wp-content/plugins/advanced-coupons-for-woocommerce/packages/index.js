/**
 * List of all javascript packages that will be loaded by webpack.
 */
const packages = {
  'acfwp-admin-app': './packages/acfwp-admin-app/index.tsx',
  'acfwp-edit-advanced-coupon': './packages/acfwp-edit-advanced-coupon/index.ts',
  'acfwp-edit-coupon-app': './packages/acfwp-edit-coupon-app/index.tsx',
  'acfwp-cart': './packages/acfwp-cart/index.ts',
  'acfwp-slmw-license': './packages/acfwp-slmw-license/index.ts',
};

module.exports = {
  packages: packages,

  // list packages that needs to be loaded via 'ts-loader'.
  tsPackages: [
    'acfwp-admin-app',
    'acfwp-edit-advanced-coupon',
    'acfwp-cart',
    'acfwp-coupon-card',
    'acfwp-slmw-license',
  ],

  // list packages that needs to be loaded via babel-react loader.
  reactPackages: ['acfwp-edit-coupon-app'],
};
