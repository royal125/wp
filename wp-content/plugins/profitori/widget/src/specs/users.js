'users'.datatype({source: 'WC', lazyCalc: true, exportable: true, caption: "Customer"})
'uniqueName'.field({essence: true, caption: 'Billing Name'})
'user_login'.field({caption: 'Login', key: true})
'user_nicename'.field({caption: 'Nice Name'})
'user_email'.field({caption: 'Email'})
'user_url'.field({caption: 'URL'})
'user_status'.field({caption: 'Status'})
'display_name'.field({caption: 'Name'})
'wp_capabilities'.field()
'billing_first_name'.field({caption: 'Billing First Name'})
'billing_last_name'.field({caption: 'Billing Last Name'})
'billing_company'.field({caption: 'Billing Company'})
'billing_address_1'.field({caption: 'Billing Address'})
'billing_address_2'.field({caption: 'Billing Address 2'})
'billing_city'.field({caption: 'Billing City'})
'billing_postcode'.field({caption: 'Billing Postal Code'})
'billing_country'.field({caption: 'Billing Country'})
'billing_state'.field({caption: 'Billing State'})
'billing_phone'.field({caption: 'Billing Phone'})
'billing_email'.field({caption: 'Billing Email'})
'shipping_first_name'.field({caption: 'Shipping First Name'})
'shipping_last_name'.field({caption: 'Shipping Last Name'})
'shipping_company'.field({caption: 'Shipping Company'})
'shipping_address_1'.field({caption: 'Shipping Address'})
'shipping_address_2'.field({caption: 'Shipping Address 2'})
'shipping_city'.field({caption: 'Shipping City'})
'shipping_postcode'.field({caption: 'Shipping Postal Code'})
'shipping_country'.field({caption: 'Shipping Country'})
'shipping_state'.field({caption: 'Shipping State'})
'first_name'.field({caption: 'First Name'})
'last_name'.field({caption: 'Last Name'})
'billingNameAndCompany'.field()
'user_id'.field({caption: 'ID', numeric: true})
'shippingAddress'.field()
'shippingNameAndCompany'.field()
'billingEmailAndPhone'.field()

'billingEmailAndPhone'.calculate(user => {
  let res = user.billing_email
  res = global.appendWithSep(res, user.billing_phone, " ")
  return res
})

'shippingNameAndCompany'.calculate(user => {
  let res = user.shipping_first_name
  res = global.appendWithSep(res, user.shipping_last_name, " ")
  res = global.appendWithSep(res, user.shipping_company, ", ")
  return res
})

'shippingAddress'.calculate(user => {
  let res = user.shipping_address_1
  res = global.appendWithSep(res, user.shipping_address_2, ", ")
  res = global.appendWithSep(res, user.shipping_city, ", ")
  res = global.appendWithSep(res, user.shipping_state, ", ")
  res = global.appendWithSep(res, user.shipping_postcode, " ")
  res = global.appendWithSep(res, user.shipping_country, " ")
  return res
})

'users'.method('toUniqueName', function() {
  let user = this
  let res = user.billing_company
  if ( ! res ) {
    res = user.billing_first_name
    res = global.appendWithSep(res, user.billing_last_name, " ")
  }
  res = global.appendWithSep(res, '(' + user.user_login + ')', " ")
  return res
})

'uniqueName'.calculate(user => {
  return user.toUniqueName()
})

'user_id'.calculate(user => {
  return user.id
})

'billingNameAndCompany'.calculate(async user => {
  let res = user.billing_first_name
  res = global.appendWithSep(res, user.billing_last_name, " ")
  res = global.appendWithSep(res, user.billing_company, ", ")
  if ( ! res )
    res = user.display_name
  let custId = user.id
  if ( custId && custId > 0 ) 
    res = global.appendWithSep(res, '(#' + custId + ')', " ")
  return res
})
