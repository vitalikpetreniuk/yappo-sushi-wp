"use strict";var __importDefault=this&&this.__importDefault||function(e){return e&&e.__esModule?e:{default:e}};Object.defineProperty(exports,"__esModule",{value:!0}),exports.Edit=void 0;const classnames_1=__importDefault(require("classnames")),currency_1=require("@woocommerce/currency"),block_editor_1=require("@wordpress/block-editor"),compose_1=require("@wordpress/compose"),core_data_1=require("@wordpress/core-data"),element_1=require("@wordpress/element"),i18n_1=require("@wordpress/i18n"),components_1=require("@wordpress/components"),use_currency_input_props_1=require("../../hooks/use-currency-input-props"),utils_1=require("../../utils"),validation_context_1=require("../../contexts/validation-context");function Edit({attributes:e,clientId:r}){const t=(0,block_editor_1.useBlockProps)(),{label:o,help:n}=e,[s]=(0,core_data_1.useEntityProp)("postType","product","regular_price"),[c,i]=(0,core_data_1.useEntityProp)("postType","product","sale_price"),u=(0,element_1.useContext)(currency_1.CurrencyContext),{getCurrencyConfig:a,formatAmount:l}=u,_=a(),p=(0,use_currency_input_props_1.useCurrencyInputProps)({value:c,setValue:i}),m=(0,compose_1.useInstanceId)(components_1.BaseControl,"wp-block-woocommerce-product-sale-price-field"),{ref:d,error:f,validate:y}=(0,validation_context_1.useValidation)(`sale-price-${r}`,(async function(){if(c){if(Number.parseFloat(c)<0)return(0,i18n_1.__)("Sale price must be greater than or equals to zero.","woocommerce");const e=Number.parseFloat(s);if(!e||e<=Number.parseFloat(c))return(0,i18n_1.__)("Sale price must be lower than the list price.","woocommerce")}}),[s,c]);return(0,element_1.createElement)("div",{...t},(0,element_1.createElement)(components_1.BaseControl,{id:m,help:f||n,className:(0,classnames_1.default)({"has-error":f})},(0,element_1.createElement)(components_1.__experimentalInputControl,{...p,id:m,name:"sale_price",ref:d,onChange:i,label:o,value:(0,utils_1.formatCurrencyDisplayValue)(String(c),_,l),onBlur:y})))}exports.Edit=Edit;