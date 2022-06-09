'TemplateMaint'.maint({panelStyle: "titled"})
'Labels Layout'.title()
'Back'.action({act: 'cancel'})
'OK'.action({act: 'ok'})
'Save'.action({act: 'save'})
'Template'.datatype()

'Layout'.panel()
'purposeDisplay'.field({readOnly: true, caption: 'Label Purpose'})

''.panel()

'Page Dimensions'.panel()
'specification'.field({hidden: true, key: true})
'pageWidthMm'.field()
'pageHeightMm'.field()
'pageLeftMarginMm'.field({numeric: true, caption: 'Page Left Margin (mm)'})
'pageTopMarginMm'.field({numeric: true, caption: 'Page Top Margin (mm)'})
'columnCount'.field({numeric: true, decimals: 0, caption: 'Number of Labels Across Page'})
'rowsPerPage'.field({numeric: true, decimals: 0, caption: 'Number of Labels Down Page'})

'Label Dimensions'.panel()
'labelWidthMm'.field({numeric: true, caption: 'Label Width (mm)'})
'labelHeightMm'.field({numeric: true, caption: 'Label Height (mm)'})
'labelHGapMm'.field({numeric: true, caption: 'Horizontal Gap Between Labels (mm)'})
'labelVGapMm'.field({numeric: true, caption: 'Vertical Gap Between Labels (mm)'})

'Fields'.manifest()
'Add Field'.action({act: 'add'})
'Facet'.datatype()
'template'.field({refersToParent: 'Template', hidden: true})
'source'.field({caption: "Get Value From"})
'caption'.field()
'left'.field()
'top'.field()
'Edit'.action({place: 'row', act: 'edit'})
'Trash'.action({place: 'row', act: 'trash'})
'FacetMaint.js'.maintSpecname()

'Fields'.defaultSort({field: "sequence"})

'TemplateMaint'.substituteCast(async (template, maint) => {
  if ( template ) 
    return template
  let specname = 'LabelsPdf.js'
  let res = await 'Template'.bringOrCreate({specification: specname})
  return res
})
