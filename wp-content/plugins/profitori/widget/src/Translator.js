
export default class Translator {

  static translate(aStr, aOptions) {
    return gTranslator.doTranslate(aStr, aOptions)
  }

  doTranslate(aStr, aOptions) {
    let str = aStr
    let supportedLanguages = ["ES", "ZH", "ID"]
    let lang = global.getLanguage()
    if ( (! lang) || (lang === "EN") )
      return str
    if ( ! supportedLanguages.contains(lang) ) 
      return str
    if ( (! this.strings) || ( lang !== this.lastLanguage) ) 
      this.strings = require("./lang/" + lang + ".json")
    this.lastLanguage = lang
    if ( aOptions && aOptions.nonEnBase )
      str = aOptions.nonEnBase
    let res = this.strings[str]; if ( ! res ) return str
    return res
  }

}

let gTranslator = new Translator()
