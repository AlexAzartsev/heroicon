import IndexField from "./components/IndexField.vue"
import DetailField from "./components/DetailField.vue"
import FormField from "./components/FormField.vue"


Nova.booting((app, store) => {
  app.component('index-heroicon', IndexField);
  app.component('detail-heroicon', DetailField);
  app.component('form-heroicon', FormField);
});
