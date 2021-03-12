<template>
  <panel-item :field="field">
    <span slot="value" class="heroicon-index" v-html="field.value">
    </span>
  </panel-item>
</template>

<script>
export default {
  props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
<style >
  .heroicon-index svg {
    width: 60px;
    height: 60px;
  }
</style>
