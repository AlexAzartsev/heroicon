/* eslint-disable global-require */
Nova.booting((Vue) => {
  Vue.component('index-heroicon', require('./components/IndexField.vue'));
  Vue.component('detail-heroicon', require('./components/DetailField.vue'));
  Vue.component('form-heroicon', require('./components/FormField.vue'));
});
