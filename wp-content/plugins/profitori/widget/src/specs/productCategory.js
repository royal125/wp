'productCategory'.datatype({source: 'WC', exportable: true})
'productId'.field({key: true, numeric: true, nonUnique: true})
'categoryId'.field({numeric: true})

'productCategory'.method('toCategory', async function() {
  return await 'category'.bringFirst({id: this.categoryId})
})
