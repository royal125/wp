'ViewCustomer'.maint({panelStyle: "titled", icon: 'ShoppingBasket'})
'View Customer'.title()
'Back'.action({act: 'cancel'})
'View Sales'.action({spec: "SACustomerOrderItemList.js"})
'Attachments'.action({act: 'attachments'})
'users'.datatype()

'Customer Information'.panel()
'user_id'.field({caption: 'Customer ID', readOnly: true})
'user_login'.field({caption: 'Login', readOnly: true})
'display_name'.field({caption: 'Name', readOnly: true})
'user_nicename'.field({caption: 'Nice Name', readOnly: true})
'user_email'.field({caption: 'Email', readOnly: true})
'user_url'.field({caption: 'URL', readOnly: true})
'user_status'.field({caption: 'Status', readOnly: true})

''.panel()

'Billing'.panel()
'billing_first_name'.field({caption: 'First Name', readOnly: true})
'billing_last_name'.field({caption: 'Last Name', readOnly: true})
'billing_company'.field({caption: 'Company', readOnly: true})
'billing_address_1'.field({caption: 'Address', readOnly: true})
'billing_address_2'.field({caption: 'Address 2', readOnly: true})
'billing_city'.field({caption: 'City', readOnly: true})
'billing_state'.field({caption: 'State', readOnly: true})
'billing_postcode'.field({caption: 'Postal Code', readOnly: true})
'billing_country'.field({caption: 'Country', readOnly: true})
'billing_phone'.field({caption: 'Phone', readOnly: true})
'billing_email'.field({caption: 'Email', readOnly: true})

'Shipping'.panel()
'shipping_first_name'.field({caption: 'First Name', readOnly: true})
'shipping_last_name'.field({caption: 'Last Name', readOnly: true})
'shipping_company'.field({caption: 'Company', readOnly: true})
'shipping_address_1'.field({caption: 'Address', readOnly: true})
'shipping_address_2'.field({caption: 'Address 2', readOnly: true})
'shipping_city'.field({caption: 'City', readOnly: true})
'shipping_state'.field({caption: 'State', readOnly: true})
'shipping_postcode'.field({caption: 'Postal Code', readOnly: true})
'shipping_country'.field({caption: 'Country', readOnly: true})
'shipping_phone'.field({caption: 'Phone', readOnly: true})
'shipping_email'.field({caption: 'Email', readOnly: true})

'ViewCustomer'.makeDestinationFor('users')

'Agents'.manifest()
'Add Agent'.action({act: 'add'})
'Tie'.datatype()
'agent'.field()
'Trash'.action({place: 'row', act: 'trash'})
'TieMaint.js'.maintSpecname()
