Nova.booting((Vue, router, store) => {
  Vue.component('index-heroicon', require('./components/IndexField'))
  Vue.component('detail-heroicon', require('./components/DetailField'))
  Vue.component('form-heroicon', require('./components/FormField'))
})
