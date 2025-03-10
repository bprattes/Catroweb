import $ from 'jquery'

export class Translation {
  constructor (translatedByLine, googleTranslateDisplayName) {
    this.translatedByLine = translatedByLine
    this.providerMap = {
      itranslate: {
        displayName: 'iTranslate',
        link: 'https://itranslate.com'
      },
      google: {
        displayName: googleTranslateDisplayName,
        link: 'https://translate.google.com'
      }
    }
    this.displayLanguageMap = {}
    this.translatedByLineMap = {}
    this.targetLanguage = null

    this.setTargetLanguage()
    this.setDisplayLanguageMap()
    this.splitTranslatedByLine()
  }

  setTargetLanguage () {
    const appLanguage = $('#app-language').data('app-language')
    this.targetLanguage = appLanguage.replace('_', '-')
  }

  setDisplayLanguageMap () {
    const self = this
    $.ajax({
      url: '../languages',
      type: 'get',
      success: function (data) {
        self.displayLanguageMap = data
      }
    })
  }

  splitTranslatedByLine () {
    const self = this
    let firstLanguage = '%sourceLanguage%'
    let secondLanguage = '%targetLanguage%'
    if (!this.isSourceLanguageFirst()) {
      firstLanguage = '%targetLanguage%'
      secondLanguage = '%sourceLanguage%'
    }

    self.translatedByLineMap = {
      before: self.translatedByLine.substring(0, self.translatedByLine.indexOf(firstLanguage)),
      between: self.translatedByLine.substring(self.translatedByLine.indexOf(firstLanguage) + firstLanguage.length, self.translatedByLine.indexOf(secondLanguage)),
      after: self.translatedByLine.substring(self.translatedByLine.indexOf(secondLanguage) + secondLanguage.length)
    }
  }

  isSourceLanguageFirst () {
    return this.translatedByLine.indexOf('%sourceLanguage%') < this.translatedByLine.indexOf('%targetLanguage%')
  }

  isTranslationNotAvailable (elementId) {
    return $(elementId).attr('lang') !== this.targetLanguage
  }

  openGoogleTranslatePage (text) {
    window.open(
      'https://translate.google.com/?q=' + encodeURIComponent(text) + '&sl=auto&tl=' + this.targetLanguage,
      '_self'
    )
  }

  setTranslationCredit (data, byLineElements) {
    $(byLineElements.before).html(this.formatProvider(this.translatedByLineMap.before, this.providerMap[data.provider]))
    $(byLineElements.between).html(this.formatProvider(this.translatedByLineMap.between, this.providerMap[data.provider]))
    $(byLineElements.after).html(this.formatProvider(this.translatedByLineMap.after, this.providerMap[data.provider]))

    if (this.isSourceLanguageFirst()) {
      $(byLineElements.firstLanguage).text(this.displayLanguageMap[data.source_language])
      $(byLineElements.secondLanguage).text(this.displayLanguageMap[data.target_language])
    } else {
      $(byLineElements.secondLanguage).text(this.displayLanguageMap[data.target_language])
      $(byLineElements.firstLanguage).text(this.displayLanguageMap[data.source_language])
    }
  }

  formatProvider (byLine, provider) {
    return byLine.replace('%provider%', '<a href="' + provider.link + '" class="translation-credit" style="text-decoration: none">' +
      provider.displayName + '</a>')
  }
}

// eslint-disable-next-line no-unused-vars
export class ByLineElementContainer {
  constructor (beforeElement, betweenElement, afterElement, firstLanguageElement, secondLanguageElement) {
    this.before = beforeElement
    this.between = betweenElement
    this.after = afterElement
    this.firstLanguage = firstLanguageElement
    this.secondLanguage = secondLanguageElement
  }
}
